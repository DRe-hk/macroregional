import fs from 'fs';
import path from 'path';
import {
  INFO_TORNEO_INICIAL,
  DELEGACIONES_INICIALES,
  DISCIPLINAS_INICIALES,
  USUARIOS_INICIALES,
  NOMINAS_INICIALES,
} from './seed-data';
import {
  InfoTorneo,
  Delegacion,
  Disciplina,
  Serie,
  Partido,
  ClasificacionItem,
  UsuarioSistema,
  AtletaInscrito,
} from './types';

interface DatabaseState {
  infoTorneo: InfoTorneo;
  delegaciones: Delegacion[];
  disciplinas: Disciplina[];
  usuarios: UsuarioSistema[];
  nominas: AtletaInscrito[];
}

const DATA_FILE_PATH = path.join(process.cwd(), 'data', 'tournament_db.json');

function initializeDatabase(): DatabaseState {
  return {
    infoTorneo: { ...INFO_TORNEO_INICIAL },
    delegaciones: JSON.parse(JSON.stringify(DELEGACIONES_INICIALES)),
    disciplinas: JSON.parse(JSON.stringify(DISCIPLINAS_INICIALES)),
    usuarios: JSON.parse(JSON.stringify(USUARIOS_INICIALES)),
    nominas: JSON.parse(JSON.stringify(NOMINAS_INICIALES)),
  };
}

export function loadDatabase(): DatabaseState {
  try {
    if (fs.existsSync(DATA_FILE_PATH)) {
      const raw = fs.readFileSync(DATA_FILE_PATH, 'utf-8');
      const parsed = JSON.parse(raw);
      if (parsed.infoTorneo && parsed.delegaciones && parsed.disciplinas) {
        if (!parsed.usuarios) parsed.usuarios = JSON.parse(JSON.stringify(USUARIOS_INICIALES));
        if (!parsed.nominas) parsed.nominas = JSON.parse(JSON.stringify(NOMINAS_INICIALES));
        return parsed;
      }
    }
  } catch (error) {
    console.error('Error leyendo base de datos, usando inicial:', error);
  }

  const initial = initializeDatabase();
  saveDatabase(initial);
  return initial;
}

export function saveDatabase(state: DatabaseState): void {
  try {
    const dir = path.dirname(DATA_FILE_PATH);
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }
    fs.writeFileSync(DATA_FILE_PATH, JSON.stringify(state, null, 2), 'utf-8');
  } catch (error) {
    console.error('Error guardando base de datos:', error);
  }
}

// Reset completo de la base de datos a limpio
export function resetDatabaseToClean(): DatabaseState {
  const clean = initializeDatabase();
  saveDatabase(clean);
  return clean;
}

// --- Operaciones de Torneo ---
export function getInfoTorneo(): InfoTorneo {
  const db = loadDatabase();
  return db.infoTorneo;
}

export function updateInfoTorneo(data: Partial<InfoTorneo>): InfoTorneo {
  const db = loadDatabase();
  db.infoTorneo = { ...db.infoTorneo, ...data };
  saveDatabase(db);
  return db.infoTorneo;
}

// --- Operaciones de Disciplinas ---
export function getDisciplinas(): Disciplina[] {
  const db = loadDatabase();
  return db.disciplinas;
}

export function getDisciplinaBySlug(slug: string): Disciplina | undefined {
  const db = loadDatabase();
  return db.disciplinas.find((d) => d.slug.toLowerCase() === slug.toLowerCase());
}

export function saveDisciplina(data: Partial<Disciplina>): Disciplina {
  const db = loadDatabase();
  const slug = (data.slug || data.nombre?.toLowerCase().replace(/\s+/g, '-')) || `dep-${Date.now()}`;

  const existingIndex = db.disciplinas.findIndex((d) => d.slug === slug || d.id === data.id);

  const nuevaDisciplina: Disciplina = {
    id: data.id || `disc-${Date.now()}`,
    slug,
    nombre: data.nombre || 'Nueva Disciplina',
    categoria: data.categoria || 'Deporte',
    colorAcento: data.colorAcento || '#2563eb',
    fotoUrl: data.fotoUrl || 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=80',
    descripcion: data.descripcion || '',
    sedePrincipal: data.sedePrincipal || 'Sede Central Puno',
    sedeMapsUrl: data.sedeMapsUrl || '',
    campeonActual: data.campeonActual || undefined,
    series: data.series || [
      {
        id: `ser-${Date.now()}-a`,
        letra: 'A',
        nombre: 'Serie Única',
        partidos: [],
      },
    ],
  };

  if (existingIndex >= 0) {
    db.disciplinas[existingIndex] = { ...db.disciplinas[existingIndex], ...nuevaDisciplina };
  } else {
    db.disciplinas.push(nuevaDisciplina);
  }

  saveDatabase(db);
  return nuevaDisciplina;
}

export function deleteDisciplina(slug: string): boolean {
  const db = loadDatabase();
  const initialLength = db.disciplinas.length;
  db.disciplinas = db.disciplinas.filter((d) => d.slug !== slug);
  if (db.disciplinas.length !== initialLength) {
    saveDatabase(db);
    return true;
  }
  return false;
}

// --- Operaciones de Series y Partidos ---
export function addSerieToDisciplina(disciplinaSlug: string, letra: string, nombre: string): Serie | null {
  const db = loadDatabase();
  const disc = db.disciplinas.find((d) => d.slug === disciplinaSlug);
  if (!disc) return null;

  const nuevaSerie: Serie = {
    id: `ser-${Date.now()}-${letra.toLowerCase()}`,
    letra: letra.toUpperCase(),
    nombre: nombre || `Serie ${letra.toUpperCase()}`,
    partidos: [],
  };

  disc.series.push(nuevaSerie);
  saveDatabase(db);
  return nuevaSerie;
}

export function addPartidoToSerie(
  disciplinaSlug: string,
  serieLetra: string,
  partidoData: Partial<Partido>
): Partido | null {
  const db = loadDatabase();
  const disc = db.disciplinas.find((d) => d.slug === disciplinaSlug);
  if (!disc) return null;

  const serie = disc.series.find((s) => s.letra.toUpperCase() === serieLetra.toUpperCase());
  if (!serie) return null;

  const nuevoPartido: Partido = {
    id: partidoData.id || `part-${Date.now()}`,
    rondaNombre: partidoData.rondaNombre || 'Ronda Eliminatoria',
    rondaNumero: partidoData.rondaNumero || 1,
    localId: partidoData.localId,
    visitanteId: partidoData.visitanteId,
    localNombre: partidoData.localNombre || 'Equipo 1',
    visitanteNombre: partidoData.visitanteNombre || 'Equipo 2',
    localGoles: null,
    visitanteGoles: null,
    esBye: partidoData.esBye || false,
    jugado: false,
    horario: partidoData.horario || 'Por definir',
    cancha: partidoData.cancha || 'Cancha 1',
    observaciones: partidoData.observaciones || '',
  };

  serie.partidos.push(nuevoPartido);
  saveDatabase(db);
  return nuevoPartido;
}

export function updatePartidoMarcador(
  disciplinaSlug: string,
  serieLetra: string,
  partidoId: string,
  localGoles: number | null,
  visitanteGoles: number | null,
  ganadorId?: string,
  observaciones?: string,
  delegacionIdPermitida?: string // Si es delegado, validar que juega su equipo
): { success: boolean; partido?: Partido; message?: string } {
  const db = loadDatabase();
  const disc = db.disciplinas.find((d) => d.slug === disciplinaSlug);
  if (!disc) return { success: false, message: 'Disciplina no encontrada' };

  const serie = disc.series.find((s) => s.letra.toUpperCase() === serieLetra.toUpperCase());
  if (!serie) return { success: false, message: 'Serie no encontrada' };

  const partido = serie.partidos.find((p) => p.id === partidoId);
  if (!partido) return { success: false, message: 'Partido no encontrado' };

  // Si tiene restricción de delegado, solo puede editar si su equipo juega en el partido
  if (delegacionIdPermitida) {
    if (partido.localId !== delegacionIdPermitida && partido.visitanteId !== delegacionIdPermitida) {
      return { success: false, message: 'No tienes permiso para modificar partidos donde no juega tu delegación.' };
    }
  }

  partido.localGoles = localGoles;
  partido.visitanteGoles = visitanteGoles;
  partido.ganadorId = ganadorId;
  if (observaciones !== undefined) {
    partido.observaciones = observaciones;
  }
  partido.jugado = localGoles !== null && visitanteGoles !== null;

  recalcularPuntosEnBase(db);

  saveDatabase(db);
  return { success: true, partido };
}

// --- Operaciones de Delegaciones / Equipos ---
export function getDelegaciones(): Delegacion[] {
  const db = loadDatabase();
  return db.delegaciones;
}

export function saveDelegacion(data: Partial<Delegacion>): Delegacion {
  const db = loadDatabase();
  const id = data.id || `del-${Date.now()}`;
  const existingIndex = db.delegaciones.findIndex((d) => d.id === id);

  const nueva: Delegacion = {
    id,
    nombre: data.nombre || 'Nueva Delegación',
    siglas: data.siglas || 'NUEVA',
    provincia: data.provincia || 'Puno',
    color: data.color || '#2563eb',
    puntos: data.puntos || 0,
    pj: data.pj || 0,
    pg: data.pg || 0,
    pp: data.pp || 0,
  };

  if (existingIndex >= 0) {
    db.delegaciones[existingIndex] = { ...db.delegaciones[existingIndex], ...nueva };
  } else {
    db.delegaciones.push(nueva);
  }

  saveDatabase(db);
  return nueva;
}

export function deleteDelegacion(id: string): boolean {
  const db = loadDatabase();
  const initial = db.delegaciones.length;
  db.delegaciones = db.delegaciones.filter((d) => d.id !== id);
  if (db.delegaciones.length !== initial) {
    saveDatabase(db);
    return true;
  }
  return false;
}

// --- Operaciones de Usuarios (Admin y Delegados) ---
export function getUsuarios(): UsuarioSistema[] {
  const db = loadDatabase();
  return db.usuarios;
}

export function saveUsuario(data: Partial<UsuarioSistema>): UsuarioSistema {
  const db = loadDatabase();
  const id = data.id || `usr-${Date.now()}`;
  const existingIndex = db.usuarios.findIndex((u) => u.id === id || u.usuario === data.usuario);

  const nuevo: UsuarioSistema = {
    id,
    usuario: data.usuario || `delegado_${Date.now()}`,
    clave: data.clave || '123456',
    nombre: data.nombre || 'Delegado Oficial',
    rol: data.rol || 'DELEGADO',
    delegacionId: data.delegacionId,
    delegacionNombre: data.delegacionNombre,
  };

  if (existingIndex >= 0) {
    db.usuarios[existingIndex] = { ...db.usuarios[existingIndex], ...nuevo };
  } else {
    db.usuarios.push(nuevo);
  }

  saveDatabase(db);
  return nuevo;
}

export function deleteUsuario(id: string): boolean {
  const db = loadDatabase();
  // Proteger al admin principal
  const user = db.usuarios.find((u) => u.id === id);
  if (user && user.usuario === 'admin') return false;

  const initial = db.usuarios.length;
  db.usuarios = db.usuarios.filter((u) => u.id !== id);
  if (db.usuarios.length !== initial) {
    saveDatabase(db);
    return true;
  }
  return false;
}

export function authenticate(usuario: string, clave: string): UsuarioSistema | null {
  const db = loadDatabase();
  const user = db.usuarios.find(
    (u) => u.usuario.trim().toLowerCase() === usuario.trim().toLowerCase() && u.clave === clave
  );
  if (user) return user;

  // Fallback demo para admin
  if (usuario.trim().toLowerCase() === 'admin' && (clave === 'drep2026' || clave === 'admin123')) {
    return db.usuarios.find((u) => u.rol === 'ADMIN') || null;
  }

  return null;
}

// --- Operaciones de Nóminas / Atletas de Delegación ---
export function getNominas(delegacionId?: string): AtletaInscrito[] {
  const db = loadDatabase();
  if (delegacionId) {
    return db.nominas.filter((n) => n.delegacionId === delegacionId);
  }
  return db.nominas;
}

export function saveAtleta(data: Partial<AtletaInscrito>): AtletaInscrito {
  const db = loadDatabase();
  const id = data.id || `atl-${Date.now()}`;
  const existingIndex = db.nominas.findIndex((a) => a.id === id);

  const nuevo: AtletaInscrito = {
    id,
    delegacionId: data.delegacionId || 'puno',
    disciplinaSlug: data.disciplinaSlug || 'futbol-libre',
    dni: data.dni || '',
    nombreCompleto: data.nombreCompleto || 'Deportista',
    numeroCamiseta: data.numeroCamiseta || '',
    rolEquipo: data.rolEquipo || 'Titular',
  };

  if (existingIndex >= 0) {
    db.nominas[existingIndex] = { ...db.nominas[existingIndex], ...nuevo };
  } else {
    db.nominas.push(nuevo);
  }

  saveDatabase(db);
  return nuevo;
}

export function deleteAtleta(id: string, delegacionId?: string): boolean {
  const db = loadDatabase();
  const atleta = db.nominas.find((a) => a.id === id);
  if (!atleta) return false;

  if (delegacionId && atleta.delegacionId !== delegacionId) {
    return false; // Permiso denegado
  }

  db.nominas = db.nominas.filter((a) => a.id !== id);
  saveDatabase(db);
  return true;
}

// --- Recálculo de Clasificación Deportiva ---
function recalcularPuntosEnBase(db: DatabaseState) {
  const stats: Record<string, { pj: number; pg: number; pp: number; puntos: number }> = {};
  db.delegaciones.forEach((del) => {
    stats[del.id] = { pj: 0, pg: 0, pp: 0, puntos: 0 };
  });

  let totalPartidos = 0;
  let partidosJugados = 0;

  db.disciplinas.forEach((disc) => {
    disc.series.forEach((serie) => {
      serie.partidos.forEach((p) => {
        totalPartidos++;
        if (p.jugado && p.localId && p.visitanteId) {
          partidosJugados++;
          const loc = stats[p.localId];
          const vis = stats[p.visitanteId];

          if (loc) loc.pj += 1;
          if (vis) vis.pj += 1;

          if (p.ganadorId === p.localId || (p.localGoles ?? 0) > (p.visitanteGoles ?? 0)) {
            if (loc) {
              loc.pg += 1;
              loc.puntos += 3;
            }
            if (vis) vis.pp += 1;
          } else if (p.ganadorId === p.visitanteId || (p.visitanteGoles ?? 0) > (p.localGoles ?? 0)) {
            if (vis) {
              vis.pg += 1;
              vis.puntos += 3;
            }
            if (loc) loc.pp += 1;
          } else {
            // Empate
            if (loc) loc.puntos += 1;
            if (vis) vis.puntos += 1;
          }
        }
      });
    });
  });

  db.delegaciones.forEach((del) => {
    const s = stats[del.id];
    if (s) {
      del.pj = s.pj;
      del.pg = s.pg;
      del.pp = s.pp;
      del.puntos = s.puntos;
    }
  });

  if (totalPartidos > 0) {
    db.infoTorneo.avancePorcentaje = Math.round((partidosJugados / totalPartidos) * 100);
  }
}

export function getClasificacionGeneral(): ClasificacionItem[] {
  const db = loadDatabase();
  const sorted = [...db.delegaciones].sort((a, b) => (b.puntos ?? 0) - (a.puntos ?? 0));

  return sorted.map((del, idx) => ({
    puesto: idx + 1,
    delegacionId: del.id,
    delegacionNombre: del.nombre,
    siglas: del.siglas,
    provincia: del.provincia,
    partidosJugados: del.pj || 0,
    partidosGanados: del.pg || 0,
    partidosPerdidos: del.pp || 0,
    puntos: del.puntos || 0,
    medallasOro: 0,
    medallasPlata: 0,
    medallasBronce: 0,
  }));
}

export function getCuadroCampeones() {
  const db = loadDatabase();
  return db.disciplinas
    .filter((d) => d.campeonActual)
    .map((d) => ({
      disciplinaSlug: d.slug,
      disciplinaNombre: d.nombre,
      categoria: d.categoria,
      oro: d.campeonActual || 'Por Definir',
    }));
}
