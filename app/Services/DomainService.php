<?php

namespace App\Services;

use App\Models\Domain;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainService
{
    public function create(Request $request): array
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                    'tld' => 'required|string'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $expirationDate = Carbon::parse(
                $this->getExpirationDate(
                    implode($request->only(['name', 'tld']))
                )->eventDate
            );
            $request->merge([
                'expiration_date' => $expirationDate->format('Y-m-d')
            ]);
            $domain = $this->saveDomain($request->all());

            return [
                'status' => true,
                'message' => 'Domínio criado com sucesso',
                'id' => $domain->id
            ];
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    protected function saveDomain(array $request): Domain
    {
        $domain = Domain::create($request);

        return $domain;
    }

    protected function getExpirationDate(string $domain): object
    {
        $whois = new Client([
            'base_uri' => env('WHOIS_URI')
        ]);

        $response = $whois->get(
            "/domain/{$domain}",
            [
                'headers' =>
                [
                    'Accept'   => "application/json",
                    'Accept'   => "application/javascript",
                ]
            ]
        );
        $return =  json_decode($response->getBody()->getContents());

        foreach ($return->events as $event) {
            if ($event->eventAction == "expiration") {
                return $event;
            }
        }
    }

    public function delete(int $id): array
    {
        try {
            Domain::find($id)->delete();

            return [
                'status' => true,
                'message' => 'Domínio deletado',
                'id' => $id
            ];
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function patch(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'id' => 'required|exists:domains,id'
                ],
                [
                    'id.exists' => 'Domínio não existe'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $update = $request->except(['id']);
            Domain::find($request->id)->update($update);

            return [
                'status' => true,
                'message' => 'Domínio atualizado com sucesso',
                'id' => $request->id
            ];
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function batch(Request $request)
    {
        try {
            $file = fopen($request->import->path(), "r");
            $header = true;
            $ids = [];

            while (($fileContent = fgetcsv($file, 255, ",")) !== FALSE) {
                if ($header) {
                    $header = false;
                    continue;
                }
                $expirationDate = Carbon::parse(
                    $this->getExpirationDate(
                        implode($fileContent)
                    )->eventDate
                );
                
                $ids[] = $this->saveDomain([
                    'name' => $fileContent[0],
                    'tld' => $fileContent[1],
                    'expiration_date' => $expirationDate
                ])->id;
            }

            return [
                'status' => true,
                'message' => 'Domínio criado com sucesso',
                'id' => implode(',', $ids)
            ];
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
