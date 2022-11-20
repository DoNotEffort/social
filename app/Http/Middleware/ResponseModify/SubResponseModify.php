<?php

namespace App\Http\Middleware\ResponseModify;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

class SubResponseModify
{
    private function fakeResponseData()
    {
        //for development
        $filename = "fake_response";
        $path = storage_path() . "/development/${filename}.json";
        $json = json_decode(file_get_contents($path), true); 
        return $json;
    }

    private function filter($data,$filterSchema)
    {
        $data =Arr::only(Arr::dot($data), $filterSchema);
        return  $data;
    }

    private function transformKeys($data,$transformSchema)
    {
        foreach ($transformSchema as $currentKey => $newKey) {
            $data[$newKey] = $data[$currentKey];
            unset($data[$currentKey]);
        }
        return  $data;
    }

    private function transformValues($data,$transformSchema)
    {
        foreach ($transformSchema as $key => $transform) {
            $data[$key] = $transform($data[$key]);
        }
        return  $data;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $status_code = $response->getStatusCode();
        if($status_code == 200){
        $content = $response->getData();
        print_r($content);
        $platform = $request->PLATFORM;
        $process = $request->PROCESS;
        $config = config('social.'.$platform);
        $filterSchema = Arr::get($config,"schema.${process}",[]);

        $transformKeySchema = Arr::get($config,"transform.keys.${process}",[]);
        $transformValueSchema = Arr::get($config,"transform.values.${process}",[]);

        if(!empty($filterSchema))
            $content = $this->filter($content,$filterSchema);

        if(!empty($transformKeySchema))
            $content = $this->transformKeys($content,$transformKeySchema);

        if(!empty($transformValueSchema))
            $content = $this->transformValues($content,$transformValueSchema);

            
        $response->setData(Arr::undot($content));
        }
        return $response;
    }
}
