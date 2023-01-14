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

            $expirationDate = Carbon::parse($this->getExpirationDate($request)->eventDate);
            $request->merge([
                'expiration_date' => $expirationDate->format('Y-m-d')
            ]);
            $domain = $this->saveDomain($request);

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

    protected function saveDomain(Request $request): Domain
    {
        $domain = Domain::create($request->all());

        return $domain;
    }

    protected function getExpirationDate(Request $request): object
    {
        $domain = implode($request->only(['name', 'tld']));


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
}
