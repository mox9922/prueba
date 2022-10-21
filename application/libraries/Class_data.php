<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Class_data
{
    public $meses = array(
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    );

    public $dia = array(
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miercoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sabado',
        7 => 'Domingo',
    );

    public $perfiles = array(
        1 => 'Administrador',
        2 => 'Filial',
        3 => 'Vigilante',
        4 => 'Medidor',
        5 => 'Super Administrador',
        6 => 'Proveedor',
    );

    public $tipo_filial = array(
        1 => 'Propietario',
        2 => 'Alquiler',
    );

    public $estado_filial = array(
        1 => 'Ocupada',
        2 => 'Desocupada',
    );

    public $tipo_abono = array(
        1 => 'Abono',
        2 => 'A Favor',
    );


    public $autos = array(
        1 => 'Automóvil',
        2 => 'Motocicleta',
        3 => 'Bicicleta',
        //4 => 'Ciclomotor',
        //5 => 'Motocarro',
        //6 => 'Automóvil de tres ruedas',
        //7 => 'Quad-Atv',
        8 => 'Carga-Liviana',


    );

    public $comprobante = array(
        1 => 'Agua',
        2 => 'Condominal',
        3 => 'Extraordinaria',
        4 => 'Deuda',
    );


    public $mascota = array(
        1 => 'Perro',
        2 => 'Gato',
        3 => 'Conejo',
        4 => 'Hámster',
        5 => 'Tortuga',
        6 => 'Hurón',
        7 => 'Cobaya',
        8 => 'Chinchilla',
        9 => 'Dragón barbudo',
        10 => 'Pájaros',
        11 => 'Cerdo miniatura',
        12 => 'Peces',
        13 => 'Ovejas',
        14 => 'Cabras',
        15 => 'Caballos',
    );


    public $tipo_propietario = array(
        1 => 'Propietario',
        2 => 'Inquilino',
    );

    public $tipo_reporte = array(
        1 => 'Reporte General',
        2 => 'Cuota Agua',
        3 => 'Cuota Condominal',
        4 => 'Cuota Extraordinaria',
        5 => 'Cuota Deuda',
    );


    public $tipo_deuda = array(
        1 => 'Agua',
        2 => 'Condominal',
        3 => 'Extraordinaria',
    );


    public $tipo_invitado = array(
        1 => 'Invitado General',
        2 => 'Invitado Unico',
    );

    public $tipo_documentos = array(
        1 => 'Cedula de Ciudadania',
        2 => 'Cedula de Juridica',
        3 => 'Cedula Extrajeria',
    );


    public $tipo_contacto = array(
        1 => 'Primario',
        2 => 'Secundario',
    );

    public $tipo_ingreso = array(
        1 => 'Filial',
        2 => 'Recurrente',
    );


    public $estado_default = array(
        1 => array('title' => 'Validar','class'    => 'btn  btn-sm btn-default'),
    );

    public $estado = array(
        1 => array('title' => 'Activa'     ,'class'    => 'btn  btn-sm btn-success'),
        2 => array('title' => 'Inactiva'   ,'class'    => 'btn  btn-sm btn-danger'),
    );


    public $estado_simple = array(
        1 => array('title' => 'Activa'     ,'class'    => 'btn  btn-sm btn-success'),
        2 => array('title' => 'Inactiva'   ,'class'    => 'btn  btn-sm btn-danger'),
    );

    public $estado_pago_simplificado = array(
        1 => array('title' => 'Pendiente'   ,'class'    => 'btn  btn-sm btn-info'),
        2 => array('title' => 'Pagada'      ,'class'    => 'btn  btn-sm btn-success'),
    );

    public $estado_pago_formal = array(
        1 => array('title' => 'Pendiente'   ,'class'    => 'btn  btn-sm btn-info'),
        2 => array('title' => 'Proceso Pago'     ,'class'    => 'btn  btn-sm btn-secondary'),
        3 => array('title' => 'Pagada'      ,'class'    => 'btn  btn-sm btn-success'),
        4 => array('title' => 'Deuda'       ,'class'    => 'btn  btn-sm btn-warning'),
        5 => array('title' => 'Rechazada'   ,'class'    => 'btn  btn-sm btn-danger'),
    );

    public $estado_deposito = array(
        1 => array('title' => 'Pendiente'   ,'class'    => 'btn  btn-sm btn-info'),
        2 => array('title' => 'Adjudicado'     ,'class'    => 'btn  btn-sm btn-success'),
    );


    public $estado_pago = array(
        1 => array('title' => 'Pendiente'   ,'class'    => 'btn  btn-sm btn-info'),
        2 => array('title' => 'Proceso'     ,'class'    => 'btn  btn-sm btn-secondary'),
        3 => array('title' => 'Pagada'      ,'class'    => 'btn  btn-sm btn-success'),
        4 => array('title' => 'Deuda'       ,'class'    => 'btn  btn-sm btn-warning'),
        5 => array('title' => 'Rechazada'   ,'class'    => 'btn  btn-sm btn-danger'),
    );

    public $restado_reserva = array(
        1 => array('title' => 'Pendiente'   ,'class'    => 'btn  btn-sm btn-default'),
        2 => array('title' => 'Validacion'  ,'class'    => 'btn  btn-sm btn-secondary'),
        3 => array('title' => 'Activa'      ,'class'    => 'btn  btn-sm btn-success'),
        4 => array('title' => 'Inactiva'    ,'class'    => 'btn  btn-sm btn-danger'),
    );

    public $restado_reserva_dias = array(
        1 => array('title' => 'Pendiente'   ,'class'    => 'btn  btn-sm btn-default'),
        2 => array('title' => 'Reservado'      ,'class'    => 'btn  btn-sm btn-success'),
        3 => array('title' => 'Rechazado'    ,'class'    => 'btn  btn-sm btn-danger'),
    );

    /*tickets*/
    public $ticket_prioridad = array(
        1 => array('title' => 'Baja'     ,'class'    => 'btn  btn-sm btn-primary'),
        2 => array('title' => 'Media'   ,'class'    => 'btn  btn-sm btn-secondary'),
        3 => array('title' => 'Media Alta'   ,'class'    => 'btn  btn-sm btn-warning'),
        4 => array('title' => 'Alta'        ,'class'    => 'btn  btn-sm btn-danger'),
    );

    public $ticket_estado = array(
        1 => array('title' => 'Espera'    ,'class'    => 'btn  btn-sm btn-default'),
        2 => array('title' => 'Tomado'    ,'class'    => 'btn  btn-sm btn-primary'),
        3 => array('title' => 'Cerrado'   ,'class'    => 'btn  btn-sm btn-success'),
    );

    public $data_estado = array(
        'ticket' => [
            1 => 'Creado',
            2 => 'Tomo',
            3 => 'Cerrado',
        ],
        'reserva' => [
            1 => 'Creada',
            2 => 'Activa',
            3 => 'Cerrado',
        ]
    );


    //estructura del menu todos los usuarios
    public $menu = array(
        //administradores
        1 => array(
            'title'     => 'Usuarios',
            'href'      => 'usuarios',
        ),
//        2 => array(
//            'title'     => 'Vigilantes',
//            'href'      => 'vigilantes',
//        ),
        3 => array(
            'title'     => 'Propietarios',
            'href'      => 'afiliales',
        ),
//        6 => array(
//            'title'     => 'Medidor',
//            'href'      => 'medidor',
//        ),

        5 => array(
            'title'     => 'Control de pagos ',
            'href'      => 'pagos',
        ),
        7 => array(
            'title'     => 'Propiedades',
            'href'      => 'propiedades',
        ),
        8 => array(
            'title'     => 'Reservas',
            'href'      => 'reservas',
        ),
        9 => array(
            'title'     => 'Deuda',
            'href'      => 'deuda',
        ),
        10 => array(
            'title'     => 'Ticket',
            'href'      => 'ticket_admin',
        ),11 => array(
            'title'     => 'Informe de Pagos ',
            'href'      => 'reporte',
        ),12 => array(
            'title'     => 'Proveedores',
            'href'      => 'servicios',
        ),13 => array(
            'title'     => 'Subir Informacion',
            'href'      => 'upload',
        ),14 => array(
            'title'     => 'Saldo a favor',
            'href'      => 'saldo_favor',
        ),15 => array(
            'title'     => 'Deposito no Identificados',
            'href'      => 'depositos',
        ),



    );
}