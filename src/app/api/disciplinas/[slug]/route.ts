import { NextResponse } from 'next/server';
import { getDisciplinaBySlug, saveDisciplina, deleteDisciplina } from '@/lib/store';

export async function GET(
  request: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  const { slug } = await params;
  const disciplina = getDisciplinaBySlug(slug);

  if (!disciplina) {
    return NextResponse.json({ error: 'Disciplina no encontrada' }, { status: 404 });
  }

  return NextResponse.json({ data: disciplina });
}

export async function PUT(
  request: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;
    const body = await request.json();
    const updated = saveDisciplina({ ...body, slug });

    return NextResponse.json({
      success: true,
      message: 'Disciplina actualizada',
      data: updated,
    });
  } catch (error) {
    return NextResponse.json({ error: 'Error al actualizar disciplina' }, { status: 500 });
  }
}

export async function DELETE(
  request: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  const { slug } = await params;
  const deleted = deleteDisciplina(slug);

  if (!deleted) {
    return NextResponse.json({ error: 'Disciplina no encontrada o ya eliminada' }, { status: 404 });
  }

  return NextResponse.json({ success: true, message: 'Disciplina eliminada' });
}
