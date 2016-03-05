BEGIN;

-- eliminar todas las tablas al inicio sin verificar constraints
SET foreign_key_checks = 0;
DROP TABLE IF EXISTS lce_cuenta_oficial CASCADE;
DROP TABLE IF EXISTS lce_cuenta_clasificacion CASCADE;
DROP TABLE IF EXISTS lce_cuenta CASCADE;
DROP TABLE IF EXISTS lce_asiento CASCADE;
DROP TABLE IF EXISTS lce_asiento_detalle CASCADE;
SET foreign_key_checks = 1;

CREATE TABLE lce_cuenta_oficial (
    codigo CHARACTER VARYING (16) PRIMARY KEY,
    cuenta CHARACTER VARYING (120) NOT NULL,
    clasificacion SMALLINT NOT NULL
);
CREATE INDEX lce_cuenta_oficial_clasificacion_idx ON lce_cuenta_oficial (clasificacion);

CREATE TABLE lce_cuenta_clasificacion (
    contribuyente INTEGER NOT NULL,
    codigo CHARACTER VARYING (10) NOT NULL,
    clasificacion CHARACTER VARYING (50) NOT NULL,
    superior CHARACTER VARYING (10),
    descripcion TEXT,
    CONSTRAINT lce_cuenta_clasificacion_pk PRIMARY KEY (contribuyente, codigo),
    CONSTRAINT lce_cuenta_clasificacion_contribuyente_fk FOREIGN KEY (contribuyente)
        REFERENCES contribuyente (rut) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_cuenta_clasificacion_contribuyente_superior_fk FOREIGN KEY (contribuyente, superior)
        REFERENCES lce_cuenta_clasificacion (contribuyente, codigo)
        ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE INDEX lce_cuenta_clasificacion_contribuyente_superior_idx ON lce_cuenta_clasificacion (contribuyente, superior);

CREATE TABLE lce_cuenta (
    contribuyente INTEGER NOT NULL,
    codigo CHARACTER VARYING (20) NOT NULL,
    cuenta CHARACTER VARYING (120) NOT NULL,
    clasificacion CHARACTER VARYING (10) NOT NULL,
    oficial CHARACTER VARYING (16),
    descripcion TEXT,
    cargos TEXT,
    abonos TEXT,
    saldo_deudor TEXT,
    saldo_acreedor TEXT,
    activa BOOLEAN NOT NULL DEFAULT true,
    codigo_otro CHARACTER VARYING (16),
    CONSTRAINT lce_cuenta_pkey PRIMARY KEY (contribuyente, codigo),
    CONSTRAINT lce_cuenta_contribuyente_fk FOREIGN KEY (contribuyente)
        REFERENCES contribuyente (rut) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_cuenta_contribuyente_clasificacion_fk FOREIGN KEY (contribuyente, clasificacion)
        REFERENCES lce_cuenta_clasificacion (contribuyente, codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_cuenta_oficial_fk FOREIGN KEY (oficial)
        REFERENCES lce_cuenta_oficial (codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE INDEX lce_cuenta_contribuyente_clasificacion_idx ON lce_cuenta (contribuyente, clasificacion);
CREATE INDEX lce_cuenta_contribuyente_activa_idx ON lce_cuenta (contribuyente, activa);

CREATE TABLE lce_asiento (
    contribuyente INTEGER NOT NULL,
    periodo SMALLINT NOT NULL CHECK (periodo = DATE_FORMAT(fecha, "%Y%m")),
    asiento INTEGER NOT NULL,
    fecha DATE NOT NULL,
    glosa TEXT NOT NULL,
    tipo CHAR(1) NOT NULL DEFAULT 'A' CHECK (tipo IN ('A', 'P', 'I')),
    json BOOLEAN NOT NULL DEFAULT false,
    anulado BOOLEAN NOT NULL DEFAULT false,
    creado DATETIME NOT NULL DEFAULT NOW(),
    modificado DATETIME,
    usuario INTEGER UNSIGNED,
    CONSTRAINT lce_asiento_pkey PRIMARY KEY (contribuyente, periodo, asiento),
    CONSTRAINT lce_asiento_contribuyente_fk FOREIGN KEY (contribuyente)
                REFERENCES contribuyente (rut) MATCH FULL
                ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_asiento_usuario_fk FOREIGN KEY (usuario)
                REFERENCES usuario (id) MATCH FULL
                ON UPDATE CASCADE ON DELETE RESTRICT
);
CREATE INDEX lce_asiento_contribuyente_periodo_tipo_idx ON lce_asiento (contribuyente, periodo, tipo);
CREATE INDEX lce_asiento_contribuyente_fecha_tipo_idx ON lce_asiento (contribuyente, fecha, tipo);

CREATE TABLE lce_asiento_detalle (
    contribuyente INTEGER NOT NULL,
    periodo SMALLINT NOT NULL,
    asiento INTEGER NOT NULL,
    movimiento SMALLINT NOT NULL,
    cuenta CHARACTER VARYING (20) NOT NULL,
    debe INTEGER,
    haber INTEGER,
    concepto TEXT,
    json BOOLEAN NOT NULL DEFAULT false,
    CONSTRAINT lce_asiento_detalle_pk PRIMARY KEY (contribuyente, periodo, asiento, movimiento),
    CONSTRAINT lce_asiento_detalle_contribuyente_periodo_asiento_fk FOREIGN KEY (contribuyente, periodo, asiento)
        REFERENCES lce_asiento (contribuyente, periodo, asiento) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_asiento_detalle_contribuyente_cuenta_fk FOREIGN KEY (contribuyente, cuenta)
        REFERENCES lce_cuenta (contribuyente, codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_asiento_detalle_debe_haber_check
        CHECK ((debe IS NOT NULL AND haber IS NULL) OR (debe IS NULL AND haber IS NOT NULL))
);
CREATE INDEX lce_asiento_detalle_contribuyente_asiento_cuenta_idx ON lce_asiento_detalle (contribuyente, asiento, cuenta);
CREATE INDEX lce_asiento_detalle_contribuyente_cuenta_idx ON lce_asiento_detalle (contribuyente, cuenta);

COMMIT;
