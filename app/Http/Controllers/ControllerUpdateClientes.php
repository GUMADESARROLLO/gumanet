<?php

namespace App\Http\Controllers;

use App\NotasCreditos;
use App\ClientesNew;
use App\ClientesOld;
use Illuminate\Http\Request;

class ControllerUpdateClientes extends Controller
{
    public function Updates()
    {
        // 1️⃣ Obtener clientes de ClientesNew que necesitan actualizarse
        $clientesNew = ClientesNew::select('CLIENTE')
            ->whereNull('DIVISION_GEOGRAFICA1')
            ->where('CLIENTE', '00182') // <- si quieres solo uno específico
            ->pluck('CLIENTE') // obtenemos solo el array de CLIENTE
            ->toArray();

        // Si no hay clientes, retornamos
        if (empty($clientesNew)) {
            return response()->json(['message' => 'No hay clientes para actualizar']);
        }

        // 2️⃣ Obtener datos desde ClientesOld basados en los clientes encontrados
        $clientesOld = ClientesOld::select('CLIENTE','DIVISION_GEOGRAFICA1','DIVISION_GEOGRAFICA2')
            ->whereIn('CLIENTE', $clientesNew)
            ->get();

        // 3️⃣ Preparar datos para actualización
        $toUpdate = [];
        foreach ($clientesOld as $cliente) {
            $toUpdate[$cliente->CLIENTE] = [
                'PAIS' => 'NI',
                'DIVISION_GEOGRAFICA1' => $cliente->DIVISION_GEOGRAFICA1,
                'DIVISION_GEOGRAFICA2' => $cliente->DIVISION_GEOGRAFICA2
            ];
        }

        // 4️⃣ Actualización masiva
        foreach ($toUpdate as $clienteCodigo => $data) {
            $Actualizados[]=[
                'CLIENTE' => $clienteCodigo,
                'data' => $data
            ];

            //ClientesNew::where('CLIENTE', $clienteCodigo)->update($data);
        }

        return response()->json([
            'message' => 'Clientes actualizados correctamente',
            'total_actualizados' => count($toUpdate),
            'data' => $Actualizados
        ]);
    }
}