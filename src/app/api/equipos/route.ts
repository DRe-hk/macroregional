import { NextResponse } from 'next/server';
import { getDelegaciones, saveDelegacion } from '@/lib/store';

export async function GET() {
  const delegaciones = getDelegaciones();
  return NextResponse.json({
    total: delegaciones.length,
    data: delegaciones,
  });
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    if (!body.nombre) {
      return NextResponse.json({ error: 'El nombre de la delegación es obligatorio' }, { status: 400 });
    }

    const nueva = saveDelegacion(body);
    return NextResponse.json({
      success: true,
      message: 'Delegación guardada exitosamente',
      data: nueva,
    });
  } catch (error) {
    return NextResponse.json({ error: 'Error al guardar delegación' }, { status: 500 });
  }
}
