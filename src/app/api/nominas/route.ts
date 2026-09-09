import { NextResponse } from 'next/server';
import { getNominas, saveAtleta, deleteAtleta } from '@/lib/store';

export async function GET(request: Request) {
  const { searchParams } = new URL(request.url);
  const delegacionId = searchParams.get('delegacionId') || undefined;

  const nominas = getNominas(delegacionId);
  return NextResponse.json({ total: nominas.length, data: nominas });
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    if (!body.nombreCompleto || !body.dni || !body.delegacionId) {
      return NextResponse.json(
        { error: 'Nombre, DNI y delegación son obligatorios' },
        { status: 400 }
      );
    }

    const nuevo = saveAtleta(body);
    return NextResponse.json({
      success: true,
      message: 'Deportista inscrito en la nómina oficial',
      data: nuevo,
    });
  } catch (error) {
    return NextResponse.json({ error: 'Error al registrar deportista' }, { status: 500 });
  }
}

export async function DELETE(request: Request) {
  const { searchParams } = new URL(request.url);
  const id = searchParams.get('id');
  const delegacionId = searchParams.get('delegacionId') || undefined;

  if (!id) {
    return NextResponse.json({ error: 'ID es obligatorio' }, { status: 400 });
  }

  const deleted = deleteAtleta(id, delegacionId);
  if (!deleted) {
    return NextResponse.json(
      { error: 'No se pudo eliminar el deportista o permiso denegado' },
      { status: 403 }
    );
  }

  return NextResponse.json({ success: true, message: 'Deportista retirado de la nómina' });
}
