export interface Delegacion {
  id: string;
  nombre: string;
  siglas: string;
  provincia: string;
  color?: string;
  puntos: number;
  pj?: number; // partidos jugados
  pg?: number; // partidos ganados
  pp?: number; // partidos perdidos
}

export interface Partido {
  id: string;
  rondaNombre: string;
  rondaNumero: number;
  localId?: string;
  visitanteId?: string;
  localNombre?: string;
  visitanteNombre?: string;
  localGoles?: number | null;
  visitanteGoles?: number | null;
  ganadorId?: string;
  esBye?: boolean;
  jugado: boolean;
  horario?: string;
  cancha?: string;
  observaciones?: string;
}

export interface Serie {
  id: string;
  letra: string; // "A", "B", "C", "FINAL"
  nombre: string;
  sedeNombre?: string;
  sedeMapsUrl?: string;
  campeonNombre?: string;
  partidos: Partido[];
}

export interface Disciplina {
  id: string;
  slug: string;
  nombre: string;
  categoria: string;
  colorAcento: string;
  fotoUrl: string;
  descripcion: string;
  sedePrincipal: string;
  sedeMapsUrl?: string;
  campeonActual?: string;
  series: Serie[];
}

export interface InfoTorneo {
  nombre: string;
  subtitulo: string;
  organizador: string;
  sedePrincipal: string;
  anio: number;
  avancePorcentaje: number;
}

export interface ClasificacionItem {
  puesto: number;
  delegacionId: string;
  delegacionNombre: string;
  siglas: string;
  provincia: string;
  partidosJugados: number;
  partidosGanados: number;
  partidosPerdidos: number;
  puntos: number;
  medallasOro: number;
  medallasPlata: number;
  medallasBronce: number;
}

export type RolUsuario = 'ADMIN' | 'DELEGADO';

export interface UsuarioSistema {
  id: string;
  usuario: string;
  clave: string;
  nombre: string;
  rol: RolUsuario;
  delegacionId?: string; // Solo para delegados
  delegacionNombre?: string;
}

export interface AtletaInscrito {
  id: string;
  delegacionId: string;
  disciplinaSlug: string;
  dni: string;
  nombreCompleto: string;
  numeroCamiseta?: string;
  rolEquipo?: string; // "Titular", "Suplente", "Capitán"
}
