<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inteligenciaMercado_model extends Model
{
    protected $table = 'tbl_comentarios';

    public function respuestas()
    {
        return $this->hasMany(IM_Comentarios::class, 'id_post', 'id'); 
    }


    public static function filtro($texto, $fecha, $fechas)
    {
        return self::query()
            ->withCount('respuestas') 
            ->when($texto, function ($q) use ($texto) {
                $q->where('Titulo', 'LIKE', "%$texto%")
                ->orWhere('Descripcion', 'LIKE', "%$texto%");
            })

            ->when($fecha, function ($q) use ($fecha) {
                $q->whereDate('Fecha', $fecha);
            })

            ->when($fechas, function ($q) use ($fechas) {
                if (is_array($fechas) && count($fechas) == 2) {
                    $q->whereBetween('Fecha', [$fechas[0], $fechas[1]]);
                }
            });
    }


}
