<?php

namespace ElfSundae\Laravel\Hashid;

class Base64Driver implements DriverInterface
{
    /**
     * Encode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function encode($data)
    {
        return urlsafe_base64_encode($data);
    }

    /**
     * Decode the data.
     *
     * @param  mixed  $data
     * @return string
     */
    public function decode($data)
    {
        return urlsafe_base64_decode($data);
    }
}
