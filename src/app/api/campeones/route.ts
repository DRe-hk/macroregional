import { NextResponse } from 'next/server';
import { getCuadroCampeones } from '@/lib/store';

export async function GET() {
  const campeones = getCuadroCampeones();
  return NextResponse.json({
    total: campeones.length,
    data: campeones,
  });
}
