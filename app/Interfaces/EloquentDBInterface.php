<?php

namespace App\Interfaces;

interface EloquentDBInterface{

	public function getAll();

	public function getPaginated();

	public function getForSelect($data);

	public function getById($id);

	public function getByColumn($term);

	public function getActively($term);
}
