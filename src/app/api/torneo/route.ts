import { NextResponse } from 'next/server';
import { getInfoTorneo, updateInfoTorneo, resetDatabaseToClean } from '@/lib/store';

export async function GET() {
  const info = getInfoTorneo();
  return NextResponse.json({ data: info });
}

export async function PUT(request: Request) {
  try {
    const body = await request.json();

    if (body.action === 'reset') {
      const clean = resetDatabaseToClean();
      return NextResponse.json({
        success: true,
        message: 'Base de datos reiniciada a valores limpios.',
        data: clean.infoTorneo,
      });
    }

    const updated = updateInfoTorneo(body);
    return NextResponse.json({
      success: true,
      message: 'Información del torneo actualizada.',
      data: updated,
    });
  } catch (error) {
    return NextResponse.json(
      { error: 'Error al actualizar información del torneo' },
      { status: 500 }
    );
  }
}
