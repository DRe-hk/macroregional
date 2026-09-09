import { NextResponse } from 'next/server';
import { getUsuarios, saveUsuario } from '@/lib/store';

export async function GET() {
  const usuarios = getUsuarios();
  return NextResponse.json({
    total: usuarios.length,
    data: usuarios,
  });
}

export async function POST(request: Request) {
  try {
    const body = await request.json();
    if (!body.usuario || !body.clave || !body.nombre) {
      return NextResponse.json(
        { error: 'Usuario, contraseña y nombre completo son requeridos' },
        { status: 400 }
      );
    }

    const nuevo = saveUsuario(body);
    return NextResponse.json({
      success: true,
      message: 'Cuenta de usuario / delegado creada exitosamente',
      data: nuevo,
    });
  } catch (error) {
    return NextResponse.json({ error: 'Error al procesar usuario' }, { status: 500 });
  }
}
