import { NextResponse } from 'next/server';
import { getClasificacionGeneral, getInfoTorneo } from '@/lib/store';

export async function GET() {
  const clasificacion = getClasificacionGeneral();
  const info = getInfoTorneo();

  return NextResponse.json({
    campeonato: info.nombre,
    avancePorcentaje: info.avancePorcentaje,
    totalEquipos: clasificacion.length,
    data: clasificacion,
  });
}
