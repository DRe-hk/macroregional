import { NextResponse } from 'next/server';
import { authenticate } from '@/lib/store';

export async function POST(request: Request) {
  try {
    const { usuario, clave } = await request.json();

    if (!usuario || !clave) {
      return NextResponse.json(
        { error: 'Usuario y contraseña son requeridos' },
        { status: 400 }
      );
    }

    const user = authenticate(usuario, clave);

    if (!user) {
      return NextResponse.json(
        { error: 'Credenciales inválidas. Comprueba tu usuario y contraseña.' },
        { status: 401 }
      );
    }

    // Retornar información segura del usuario
    const { clave: _, ...usuarioSeguro } = user;

    return NextResponse.json({
      success: true,
      user: usuarioSeguro,
    });
  } catch (error) {
    return NextResponse.json(
      { error: 'Error durante la autenticación' },
      { status: 500 }
    );
  }
}
