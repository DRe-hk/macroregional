import { NextResponse } from 'next/server';
import { saveDelegacion, deleteDelegacion } from '@/lib/store';

export async function PUT(
  request: Request,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    const body = await request.json();
    const updated = saveDelegacion({ ...body, id });

    return NextResponse.json({
      success: true,
      message: 'Delegación actualizada',
      data: updated,
    });
  } catch (error) {
    return NextResponse.json({ error: 'Error al actualizar delegación' }, { status: 500 });
  }
}

export async function DELETE(
  request: Request,
  { params }: { params: Promise<{ id: string }> }
) {
  const { id } = await params;
  const deleted = deleteDelegacion(id);

  if (!deleted) {
    return NextResponse.json({ error: 'Delegación no encontrada o ya eliminada' }, { status: 404 });
  }

  return NextResponse.json({ success: true, message: 'Delegación eliminada' });
}
