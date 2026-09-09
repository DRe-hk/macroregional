import { NextResponse } from 'next/server';
import { updatePartidoMarcador, addPartidoToSerie } from '@/lib/store';

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const { action, disciplinaSlug, serieLetra } = body;

    if (!disciplinaSlug || !serieLetra) {
      return NextResponse.json(
        { error: 'disciplinaSlug y serieLetra son requeridos' },
        { status: 400 }
      );
    }

    // Caso 1: Crear nuevo partido
    if (action === 'crear') {
      const nuevo = addPartidoToSerie(disciplinaSlug, serieLetra, body.partido);
      if (!nuevo) {
        return NextResponse.json({ error: 'No se pudo crear el partido' }, { status: 404 });
      }
      return NextResponse.json({
        success: true,
        message: 'Partido programado con éxito',
        partido: nuevo,
      });
    }

    // Caso 2: Actualizar marcador de partido
    const { partidoId, localGoles, visitanteGoles, ganadorId } = body;
    if (!partidoId) {
      return NextResponse.json({ error: 'partidoId es requerido' }, { status: 400 });
    }

    const gLoc = localGoles === null || localGoles === '' ? null : Number(localGoles);
    const gVis = visitanteGoles === null || visitanteGoles === '' ? null : Number(visitanteGoles);

    const result = updatePartidoMarcador(
      disciplinaSlug,
      serieLetra,
      partidoId,
      gLoc,
      gVis,
      ganadorId
    );

    if (!result.success) {
      return NextResponse.json({ error: result.message }, { status: 404 });
    }

    return NextResponse.json({
      success: true,
      message: 'Marcador actualizado en la base de datos',
      partido: result.partido,
    });
  } catch (error) {
    return NextResponse.json({ error: 'Error procesando solicitud' }, { status: 500 });
  }
}
