<?php

namespace App\Http\Middleware\ResponseModify;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

class MainResponseModify
{
    private function fakeResponseData()
    {
        //for development
        $filename = "fake_response";
        $path = storage_path() . "/development/${filename}.json";
        $json = json_decode(file_get_contents($path), true);
        return $json;
    }

    private function scan($content, $schemas)
    {
        $content = json_decode(json_encode($content), true); // for Arr::isList must be array
        $contentClone=[];
        foreach ($content as $mainId => $subItem) {
            if (Arr::isList($subItem)) {
                return $this->scan($subItem, $schemas);
            } else {
                $contentClone[$mainId] = $subItem;
                foreach ($subItem as $key => $value) {
                    $contentClone[$mainId][$key] = $this->modify($value, $schemas);
                }
                return $contentClone;
            }

        }
    }

    private function modify($content, $schemas)
    {
        if (!empty($schemas["filter"]))
            $content = $this->filter($content, $schemas["filter"]);

        if (!empty($schemas["transform_keys"]))
            $content = $this->transformKeys($content, $schemas["transform_keys"]);

        if (!empty($schemas["transform_values"]))
            $content = $this->transformValues($content, $schemas["transform_values"]);

        return Arr::undot($content);
    }

    private function filter($data, $filterSchema)
    {
        $data = Arr::only(Arr::dot($data), $filterSchema);
        return  $data;
    }

    private function transformKeys($data, $transformSchema)
    {
        foreach ($transformSchema as $currentKey => $newKey) {
            $data[$newKey] = $data[$currentKey];
            unset($data[$currentKey]);
        }
        return  $data;
    }

    private function transformValues($data, $transformSchema)
    {
        foreach ($transformSchema as $key => $transform) {
            $data[$key] = $transform($data[$key]);
        }
        return  $data;
    }


    private function getSchemas($platform, $process)
    {
        $config = config('social.' . $platform);

        return [
            "filter" => Arr::get($config, "schema.${process}", []),
            "transform_keys" => Arr::get($config, "transform.keys.${process}", []),
            "transform_values" => Arr::get($config, "transform.values.${process}", [])
        ];
    }

    public function handle(Request $request, Closure $next)
    {
        $test_data = false;

        $response = $next($request);
        $status_code = $response->getStatusCode();
        if ($status_code == 200) {
            $content = (array) ($test_data ? $this->fakeResponseData() : $response->getData());
            $platform = $request->PLATFORM;
            $process = $request->PROCESS;

            $schemas = $this->getSchemas($platform, $process);
            $content = $this->scan($content, $schemas);
            $response->setData($content);
        }
        return $response;
    }
}
