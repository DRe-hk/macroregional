import { NextResponse } from 'next/server';
import { getDisciplinas, saveDisciplina } from '@/lib/store';

export async function GET() {
  const disciplinas = getDisciplinas();
  return NextResponse.json({
    total: disciplinas.length,
    data: disciplinas,
  });
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    if (!body.nombre) {
      return NextResponse.json({ error: 'El nombre del deporte es obligatorio' }, { status: 400 });
    }

    const nueva = saveDisciplina(body);
    return NextResponse.json({
      success: true,
      message: 'Disciplina guardada con éxito',
      data: nueva,
    });
  } catch (error) {
    return NextResponse.json({ error: 'Error al guardar disciplina' }, { status: 500 });
  }
}
