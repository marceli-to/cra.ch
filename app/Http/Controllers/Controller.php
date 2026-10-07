<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

abstract class Controller
{
  /**
   * The list of a reorder request: `{$key: [{id, order}, ...]}`
   */
  protected function orderItems(Request $request, string $key): array
  {
    return $request->validate([
      $key => 'required|array',
      "{$key}.*.id" => 'required|integer',
      "{$key}.*.order" => 'required|integer',
    ])[$key];
  }
}
