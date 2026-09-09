import { NextResponse } from 'next/server';
import { deleteUsuario } from '@/lib/store';

export async function DELETE(
  request: Request,
  { params }: { params: Promise<{ id: string }> }
) {
  const { id } = await params;
  const deleted = deleteUsuario(id);

  if (!deleted) {
    return NextResponse.json(
      { error: 'No se pudo eliminar el usuario (el administrador principal no puede ser borrado)' },
      { status: 400 }
    );
  }

  return NextResponse.json({ success: true, message: 'Usuario eliminado' });
}
