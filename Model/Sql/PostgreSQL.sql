BEGIN;

DROP TABLE IF EXISTS lce_cuenta_clasificacion CASCADE;
CREATE TABLE lce_cuenta_clasificacion (
    codigo CHARACTER VARYING (3) PRIMARY KEY,
    clasificacion CHARACTER VARYING (50) NOT NULL,
    superior CHARACTER VARYING (3),
    descripcion TEXT,
    CONSTRAINT lce_cuenta_clasificacion_superior_fk FOREIGN KEY (superior)
        REFERENCES lce_cuenta_clasificacion (codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE INDEX ON lce_cuenta_clasificacion (superior);
COMMENT ON TABLE lce_cuenta_clasificacion IS 'Clasificación y subclasificación de cuentas contables';
COMMENT ON COLUMN lce_cuenta_clasificacion.codigo IS 'Código de la clasificación';
COMMENT ON COLUMN lce_cuenta_clasificacion.clasificacion IS 'Nombre de la clasificación';
COMMENT ON COLUMN lce_cuenta_clasificacion.superior IS 'Clasificación superior en caso de tener una';
COMMENT ON COLUMN lce_cuenta_clasificacion.descripcion IS 'Descripción de la clasificación (tipo de cuentas que contiene)';

DROP TABLE IF EXISTS lce_cuenta_oficial CASCADE;
CREATE TABLE lce_cuenta_oficial (
    codigo CHARACTER VARYING (16) PRIMARY KEY,
    cuenta CHARACTER VARYING (120) NOT NULL,
    clasificacion CHARACTER VARYING (3) NOT NULL,
    CONSTRAINT lce_cuenta_oficial_clasificacion_fk FOREIGN KEY (clasificacion)
        REFERENCES lce_cuenta_clasificacion (codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE INDEX ON lce_cuenta_oficial (clasificacion);
COMMENT ON TABLE lce_cuenta_oficial IS 'Plan de cuentas oficial del SII, cuentas de la empresa se deben mapear a estas para construir el diccionario de cuentas';
COMMENT ON COLUMN lce_cuenta_oficial.codigo IS 'Código asignado por el SII a la cuenta';
COMMENT ON COLUMN lce_cuenta_oficial.cuenta IS 'Nombre asignado por el SII a la cuenta';
COMMENT ON COLUMN lce_cuenta_oficial.clasificacion IS 'Clasificación de la cuenta';

DROP TABLE IF EXISTS lce_cuenta CASCADE;
CREATE TABLE lce_cuenta (
    contribuyente INTEGER NOT NULL,
    codigo CHARACTER VARYING (20) NOT NULL,
    cuenta CHARACTER VARYING (120) NOT NULL,
    clasificacion CHARACTER VARYING (3) NOT NULL,
    subclasificacion CHARACTER VARYING (3) NOT NULL,
    oficial CHARACTER VARYING (16) NOT NULL,
    descripcion TEXT NOT NULL,
    cargos TEXT,
    abonos TEXT,
    saldo_deudor TEXT,
    saldo_acreedor TEXT,
    activa BOOLEAN NOT NULL DEFAULT true,
    CONSTRAINT lce_cuenta_pkey PRIMARY KEY (contribuyente, codigo),
    CONSTRAINT lce_cuenta_contribuyente_fk FOREIGN KEY (contribuyente)
                REFERENCES contribuyente (rut) MATCH FULL
                ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_cuenta_clasificacion_fk FOREIGN KEY (clasificacion)
        REFERENCES lce_cuenta_clasificacion (codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_cuenta_subclasificacion_fk FOREIGN KEY (subclasificacion)
        REFERENCES lce_cuenta_clasificacion (codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_cuenta_oficial_fk FOREIGN KEY (oficial)
        REFERENCES lce_cuenta_oficial (codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE INDEX ON lce_cuenta (contribuyente, clasificacion);
CREATE INDEX ON lce_cuenta (contribuyente, subclasificacion);
CREATE INDEX ON lce_cuenta (contribuyente, activa);
COMMENT ON TABLE lce_cuenta IS 'Plan de cuentas de la empresa (por ejemplo plan de cuentas MiPyme SII)';
COMMENT ON COLUMN lce_cuenta.contribuyente IS 'RUT del contribuyente sin DV';
COMMENT ON COLUMN lce_cuenta.codigo IS 'Código de la cuenta (recomendado jerárquico)';
COMMENT ON COLUMN lce_cuenta.cuenta IS 'Nombre corto de la cuenta';
COMMENT ON COLUMN lce_cuenta.clasificacion IS 'Clasificación de la cuenta (Activo, Pasivo, Patrimonio o Resultado)';
COMMENT ON COLUMN lce_cuenta.subclasificacion IS 'Clasificación dentro de las de mayor jerarquía, por ejemplo Activo Circulante';
COMMENT ON COLUMN lce_cuenta.oficial IS 'Correspondencia de esta cuenta con una cuenta oficial del SII (para confección de diccionario de cuentas)';
COMMENT ON COLUMN lce_cuenta.descripcion IS 'Descripción de la cuenta';
COMMENT ON COLUMN lce_cuenta.cargos IS 'Cuando se debe hacer un cargo a la cuenta';
COMMENT ON COLUMN lce_cuenta.abonos IS 'Cuando se debe hacer un abono a la cuenta';
COMMENT ON COLUMN lce_cuenta.saldo_deudor IS 'Que representa el saldo deudor de la cuenta';
COMMENT ON COLUMN lce_cuenta.saldo_acreedor IS 'Que representa el saldo acreedor de la cuenta';
COMMENT ON COLUMN lce_cuenta.activa IS 'Indica si la cuenta se puede o no usar';

DROP TABLE IF EXISTS lce_asiento CASCADE;
CREATE TABLE lce_asiento (
    contribuyente INTEGER NOT NULL,
    codigo INTEGER NOT NULL,
    fecha DATE NOT NULL,
    glosa TEXT NOT NULL,
    json BOOLEAN NOT NULL DEFAULT false,
    anulado BOOLEAN NOT NULL DEFAULT false,
    creado TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
    modificado TIMESTAMP WITHOUT TIME ZONE,
    usuario INTEGER,
    CONSTRAINT lce_asiento_pkey PRIMARY KEY (contribuyente, codigo),
    CONSTRAINT lce_asiento_contribuyente_fk FOREIGN KEY (contribuyente)
                REFERENCES contribuyente (rut) MATCH FULL
                ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_asiento_usuario_fk FOREIGN KEY (usuario)
                REFERENCES usuario (id) MATCH FULL
                ON UPDATE CASCADE ON DELETE RESTRICT
);
CREATE INDEX ON lce_asiento (contribuyente, fecha);
COMMENT ON TABLE lce_asiento IS 'Cabecera de los asientos contables';
COMMENT ON COLUMN lce_asiento.contribuyente IS 'RUT del contribuyente sin DV';
COMMENT ON COLUMN lce_asiento.codigo IS 'Identificador único del asiento para el contribuyente';
COMMENT ON COLUMN lce_asiento.fecha IS 'Fecha del hecho económico que se está registrando';
COMMENT ON COLUMN lce_asiento.glosa IS 'Glosa o descripción del hecho económico';

DROP TABLE IF EXISTS lce_asiento_detalle CASCADE;
CREATE TABLE lce_asiento_detalle (
    contribuyente INTEGER NOT NULL,
    asiento INTEGER NOT NULL,
    cuenta CHARACTER VARYING (20) NOT NULL,
    debe INTEGER,
    haber INTEGER,
    concepto TEXT,
    json BOOLEAN NOT NULL DEFAULT false,
    CONSTRAINT lce_asiento_detalle_contribuyente_asiento_fk FOREIGN KEY (contribuyente, asiento)
        REFERENCES lce_asiento (contribuyente, codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_asiento_detalle_contribuyente_cuenta_fk FOREIGN KEY (contribuyente, cuenta)
        REFERENCES lce_cuenta (contribuyente, codigo) MATCH FULL
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT lce_asiento_detalle_debe_haber_check
        CHECK ((debe IS NOT NULL AND haber IS NULL) OR (debe IS NULL AND haber IS NOT NULL))
);
CREATE INDEX ON lce_asiento_detalle (contribuyente, asiento, cuenta);
CREATE INDEX ON lce_asiento_detalle (contribuyente, cuenta);
COMMENT ON TABLE lce_asiento_detalle IS 'Detalle de los asientos contables';
COMMENT ON COLUMN lce_asiento_detalle.contribuyente IS 'RUT del contribuyente sin DV';
COMMENT ON COLUMN lce_asiento_detalle.asiento IS 'Número de asiento';
COMMENT ON COLUMN lce_asiento_detalle.cuenta IS 'Cuenta que se ve afectada';
COMMENT ON COLUMN lce_asiento_detalle.debe IS 'Cargo al debe';
COMMENT ON COLUMN lce_asiento_detalle.haber IS 'Abono al haber';

COMMIT;
