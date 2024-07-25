<?php
namespace App\Repositories\Product;

use App\Models\Product\Product;
use App\Models\Artisan\Artisan;
use App\Models\Category\Category;
use App\Repositories\EloquentDBRepository;
use App\Models\FileInfo\FileInfo;
use App\Repositories\FileRepository;
use Sentinel;
use DB;
class ProductRepository extends EloquentDBRepository
{

    protected $product;
    protected $fileinfoRepo;
    public function __construct(Product $product,FileRepository $fileinfoRepo)
    {
        $this->model = $product;
        $this->fileinfoRepo = $fileinfoRepo;
    }
    public function getInformation($id){
        $product = $this->model->with(['productImages'])->find($id);
        return $product;
    }

    public function search($search,$stock_search,$artisan_search){
        $products = (new Product)->newQuery();
        $products->where(function ($query) use ($search) {
                $query->whereHas('category', function ($in_query) use ($search){
                    $in_query->where('product_name','like','%'.$search.'%')
                        ->orwhere('category_name','like','%'.$search.'%');
                });
        })->where(function ($query) use ($stock_search) {
            $query->where('stock_status','like','%'.$stock_search.'%');
        })->where(function ($query) use ($artisan_search) {
                $query->whereHas('artisan', function ($in_query) use ($artisan_search){
                    $in_query->where('first_name','like','%'.$artisan_search.'%')
                    ->orWhere('last_name','like','%'.$artisan_search.'%')
                    ->orWhere(function($query) use ($artisan_search) {
                        $query->where(\DB::raw("concat(`artisans`.`first_name`,' ',`artisans`.`last_name`)"),'like', "%".$artisan_search."%");
                    });
                });
        });
        return $products;
    }
    
    public function manageStock(Product $product, $attributes)
    {
        $data = [
            'stock_status' => $attributes['stock_status'],
            'stock' => $attributes['stock']
        ];
        $response = $product->update($data);
        return $response;
    }
    public function createProduct($file,$attributes)
    {
        $is_approved=$attributes['is_approved'];
        $base_price=(int)($attributes['base_price']);
        $commission_amount=0;
        $applied_commision=null;
        $applied_commission_number=0; 
        $applied_commision_type=null; 
        
        //product level commission
        
        $product_comm_number=(int)($attributes['commission']);
        $product_comm_type=null;
        if($product_comm_number!=null)
        {
            $product_comm_type=$attributes['commission_unit'];
            if($product_comm_type=='rupee')
            {
                $commission_amount=$product_comm_number;
            }
            else if($product_comm_type=='percentage')
            {
                $commission_amount=($base_price*$product_comm_number)/100;
            }
            $applied_commision='product';
            $applied_commission_number=$product_comm_number;
            $applied_commision_type=$product_comm_number;

        }
        //sub category level commission
        $sub_category_id=$attributes['sub_category_id'];
        $category_comm_number=null;
        $category_comm_type=null;
        if($product_comm_number==null)
        {
            $category=Category::where('id',$sub_category_id)->first();
            $category_comm_number=(int)($category->commission);
            if($category_comm_number!=null)
            {
                $category_comm_type='percentage';
                $commission_amount=($base_price*$category_comm_number)/100;
            }
            $applied_commision='sub category';
            $applied_commission_number=$category_comm_number;
            $applied_commision_type=$category_comm_type;
        }
        //artisan level 
        $artisan_comm_number=null;
        $artisan_comm_type=null;
        $artisan_id=$attributes['artisan_id'];
        if($product_comm_number==null && $category_comm_number==null)
        {
            $artisan=Artisan::where('id',$artisan_id)->first();
            $artisan_comm_number=(int)$artisan->commission;
            if($artisan_comm_number!=null)
            {
            $artisan_comm_type='percentage';
            $commission_amount=($base_price*$artisan_comm_number)/100;
            }
            $applied_commision='artisan';
            $applied_commission_number=$artisan_comm_number;
            $applied_commision_type=$artisan_comm_type;
        }
        //global level commission
        $global_comm_type=null;
        $global_comm_number=null;
        if($product_comm_number==null && $category_comm_number==null && $artisan_comm_number==null)
        {
            $global_comm_number=25;
            $global_comm_type='percentage';
            $commission_amount=($base_price*$global_comm_number)/100;
            $applied_commision='global';
            $applied_commission_number=$global_comm_number;
            $applied_commision_type=$global_comm_type;
        }
        $selling_price=$base_price+$commission_amount;
        //tax calculation
        $tax_class=$attributes['tax_class'];
        if($tax_class==0)
        {
            $tax_amount=0;
        }
        else
        {
            $tax_amount=($selling_price*$tax_class)/100;
        }
        $mrp=$selling_price+$tax_amount;
        $data = [
            'artisan_id' => $artisan_id,
            'category_id' => $attributes['category_id'],
            'sub_category_id' => $sub_category_id,
            'product_name' => $attributes['product_name'],
            // 'sku' => $attributes['sku'],
            'short_desc' => $attributes['short_desc'],
            'long_desc' => $attributes['long_desc'],
            'material' => $attributes['material'],
            'stock' => $attributes['stock'],
            'stock_status' => $attributes['stock_status'],
            'base_price' => $base_price,
            'product_comm_number' => $product_comm_number,
            'product_comm_type' => $product_comm_type,
            'category_comm_number' => $category_comm_number,
            'category_comm_type' => $category_comm_type,
            'artisan_comm_number' => $artisan_comm_number,
            'artisan_comm_type' => $artisan_comm_type,
            'global_comm_number' => $global_comm_number,
            'global_comm_type' => $global_comm_type,
            'selling_price' => $selling_price,
            'commision_amount' => $commission_amount,
            'tax_status' => $attributes['tax_status'],
            'tax_class' => config('constant.tax_class')[$tax_class],
            'tax_amount' => $tax_amount,
            'mrp' => $mrp, 
            'hsn_code' => $attributes['hsn_code'],
            'status' => ($is_approved == 1) ? 'approved' : 'pending'
        ];
        $response = $this->model->create($data);

        if($file != null){
            $dir = 'products/product-gallery/'.$response->id_token;
            $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
            // $response->update(['product_image'=>$fileinfo->id]);
            $response->productImages()->create([
                'file_id'   => $fileinfo->id,
                'file_type' => 'main'
            ]);
        }

        //create entry in price history
        $price_history=[
            'user_id' => Sentinel::getUser()->id,
            'base_price' => $base_price,
            'applied_commision' => $applied_commision,
            'applied_commission_number'=> $applied_commission_number,
            'applied_commision_type' => $applied_commision_type,
            'commision_amount' => $commission_amount,
            'selling_price' => $selling_price,
            'tax' => $tax_amount,
            'mrp' => $mrp
        ];
        $res = $response->productPriceHistories()->create($price_history);
        return $response;

    }
    public function createProductGallery(Product $product, $request)
    {
        if($request->hasFile('product_gallery')){
            foreach ($request->file('product_gallery') as $file) {
                $dir = 'products/product-gallery/' . $product->id_token;
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $product->productImages()->create([
                    'file_id'   => $fileinfo->id
                ]);
            }
        }
        return true;
    }

    public function createProductPriceHistory(Product $product,$attributes)
    {
        $data = [
            'user_id' => Sentinel::getUser()->id,
            'base_price' => $attributes['base_price']
        ];
        $response = $product->productPriceHistories()->create($data);
        return $response;
    }
    public function createProductStockHistory(Product $product,$attributes)
    {
        $data = [
            'user_id' => Sentinel::getUser()->id,
            'stock_status' => $attributes['stock_status'],
            'stock' => $attributes['stock']

        ];
        $response = $product->productStockHistories()->create($data);
        return $response;
    }
    public function updateProduct(Product $product, $file ,$attributes)
    {
        //store old values before update because so that if value changes then only enter in price history table
        $base_price_record=$product->productPriceHistories()->latest('created_at')->get()->first();
        $old_base_price=$product->base_price;
        $old_applied_commision=$base_price_record['applied_commision'];
        $old_applied_commission_number=$base_price_record['applied_commission_number'];
        $old_applied_commision_type=$base_price_record['applied_commision_type'];
        $old_commision_amount=$base_price_record['commision_amount'];
        $old_selling_price=$base_price_record['selling_price'];
        $old_tax=$base_price_record['tax'];
        $old_mrp=$base_price_record['mrp'];

        
        $base_price=(int)($attributes['base_price']);
        $applied_commision=null;
        $applied_commission_number=0; 
        $applied_commision_type=null;
        //product level commission
        
        $product_comm_number=(int)($attributes['commission']);
        $product_comm_type=null;
        if($product_comm_number!=null)
        {
            $product_comm_type=$attributes['commission_unit'];
            if($product_comm_type=='rupee')
            {
                $commission_amount=$product_comm_number;
            }
            else if($product_comm_type=='percentage')
            {
                $commission_amount=($base_price*$product_comm_number)/100;
            }
            $applied_commision='product';
            $applied_commission_number=$product_comm_number;
            $applied_commision_type=$product_comm_number;

        }
        //sub category level commission
        $sub_category_id=$attributes['sub_category_id'];
        $category_comm_number=null;
        $category_comm_type=null;
        if($product_comm_number==null)
        {
            $category=Category::where('id',$sub_category_id)->first();
            $category_comm_number=(int)($category->commission);
            if($category_comm_number!=null)
            {
                $category_comm_type='percentage';
                $commission_amount=($base_price*$category_comm_number)/100;
            }
            $applied_commision='sub category';
            $applied_commission_number=$category_comm_number;
            $applied_commision_type=$category_comm_type;
        }
        //artisan level 
        $artisan_comm_number=null;
        $artisan_comm_type=null;
        $artisan_id=$attributes['artisan_id'];
        if($product_comm_number==null && $category_comm_number==null)
        {
            $artisan=Artisan::where('id',$artisan_id)->first();
            $artisan_comm_number=(int)$artisan->commission;
            if($artisan_comm_number!=null)
            {
            $artisan_comm_type='percentage';
            $commission_amount=($base_price*$artisan_comm_number)/100;
            }
            $applied_commision='artisan';
            $applied_commission_number=$artisan_comm_number;
            $applied_commision_type=$artisan_comm_type;
        }
        //global level commission
        $global_comm_type=null;
        $global_comm_number=null;
        if($product_comm_number==null && $category_comm_number==null && $artisan_comm_number==null)
        {
            $global_comm_number=25;
            $global_comm_type='percentage';
            $commission_amount=($base_price*$global_comm_number)/100;
            $applied_commision='global';
            $applied_commission_number=$global_comm_number;
            $applied_commision_type=$global_comm_type;
        }
        $selling_price=$base_price+$commission_amount;
        //tax calculation
        $tax_class=(int)$attributes['tax_class'];
        if($tax_class==0)
        {
            $tax_amount=0;
        }
        else
        {
            $tax_amount=($selling_price*$tax_class)/100;
        }
        $mrp=$selling_price+$tax_amount;
        $data = [
            'artisan_id' => $artisan_id,
            'category_id' => $attributes['category_id'],
            'sub_category_id' => $sub_category_id,
            'product_name' => $attributes['product_name'],
            // 'sku' => $attributes['sku'],
            'short_desc' => $attributes['short_desc'],
            'long_desc' => $attributes['long_desc'],
            'material' => $attributes['material'],
            'stock' => $attributes['stock'],
            'stock_status' => $attributes['stock_status'],
            'base_price' => $base_price,
            'product_comm_number' => $product_comm_number,
            'product_comm_type' => $product_comm_type,
            'category_comm_number' => $category_comm_number,
            'category_comm_type' => $category_comm_type,
            'artisan_comm_number' => $artisan_comm_number,
            'artisan_comm_type' => $artisan_comm_type,
            'global_comm_number' => $global_comm_number,
            'global_comm_type' => $global_comm_type,
            'selling_price' => $selling_price,
            'commision_amount' => $commission_amount,
            'tax_status' => $attributes['tax_status'],
            'tax_class' => $tax_class,
            'tax_amount' => $tax_amount,
            'mrp' => $mrp, 
            'hsn_code' => $attributes['hsn_code']
        ];
        $response = $product->update($data);

        //create entry in price history
        if($old_base_price!=$base_price || $old_applied_commision!=$applied_commision || $old_applied_commission_number!=$applied_commission_number
        || $old_applied_commision_type!=$applied_commision_type || $old_commision_amount!=$commission_amount || $old_selling_price!=$selling_price
        || $old_tax!=$tax_amount || $old_mrp!=$mrp)
        {
            $price_history=[
                'user_id' => Sentinel::getUser()->id,
                'base_price' => $base_price,
                'applied_commision' => $applied_commision,
                'applied_commission_number'=> $applied_commission_number,
                'applied_commision_type' => $applied_commision_type,
                'commision_amount' => $commission_amount,
                'selling_price' => $selling_price,
                'tax' => $tax_amount,
                'mrp' => $mrp
            ];
            $res = $product->productPriceHistories()->create($price_history);
        }
        return $response;
    }

    public function updateImages(Product $product, $request) 
    {
        //update main product image
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $fileInfo = NULL;
            // if (!empty($artisan->photo())) {
                $product_main_image=$product->productImages()->where('file_type','main')->select('file_id')->first();
                // $file_id=$product_main_image['file_id'];
                // $fileInfo = FileInfo::where('id',$file_id)->first();
                $fileInfo = $product_main_image->fileInfo()->first();
            // }
            $dir = 'products/product-gallery/' . $product->id_token;
            if (!is_null($fileInfo)) {
                $this->fileinfoRepo->uploadAndUpdateImageFile($fileInfo, $dir, $file, getStorageDisk(), true);
            } else {
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $product->productImages()->create([
                    'file_id'   => $fileinfo->id,
                    'file_type' => 'main'
                ]);
            }
        }
        //update product gallery
        if ($request->hasFile('product_gallery')) {
            $productgallery = $product->productImages()->pluck('file_id');
            $fileInfos = FileInfo::find($productgallery->toArray());
            $product->productImages()->forceDelete();
            if ($fileInfos->count()) {
                foreach ($fileInfos as $fileInfo) {
                    $this->fileinfoRepo->removeFileWithFileInfo($fileInfo, getStorageDisk());
                }
            }
            foreach ($request->file('product_gallery') as $file) {
                $dir = 'products/product-gallery/' . $product->id_token;
                $fileinfo = $this->fileinfoRepo->uploadAndCreateImageFile($dir, $file, getStorageDisk(), true);
                $product->productImages()->create([
                    'file_id'   => $fileinfo->id
                ]);
            }
        }
        return true;
    }
    public function approveProduct($id){
        $product = Product::find($id);
        if($product){
            $product->status = 'approved';
            $product->save();
            return $product;
        }
        return false;
    }
    public function rejectProduct($id){
        $product = Product::find($id);
        if($product){
            $product->status = 'rejected';
            $product->save();
            return $product;
        }
    }

    public function banProduct(Product $product)
    {
        //TODO::implement Auth repo ban user
        return $product->update(['delisted' => 1]);
    }

    public function unbanProduct(Product $product)
    {
        //TODO::implement Auth repo ban user
        return $product->update(['delisted' => 0]);
    }
}

