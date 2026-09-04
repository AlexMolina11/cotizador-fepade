<?php
//Incluimos la conexion a la base de datos
require "../config/Conexion.php";
class Rol{
    //Implementar nuestro constructor
    public function __construct()
    {

    }
    
    //Implementamos un método para insertar registros

    public function rolunico($nombrerol){
        $sql="SELECT COUNT(*) cantidad FROM rol WHERE NOMBREROL like '%$nombrerol%'"; 
        return ejecutarConsulta($sql);
    }

    public function insertar($nombrerol,$regusu,$permiso,$ipusu,$acceso,$accesoacciones,$arearesponsable){
       
        $sql="INSERT INTO rol(NOMBREROL,USUREGROL,PERMISOROL,PERMISOAREARES,IPREGROL,FECHAREGROL) values('$nombrerol','$regusu','$permiso','$arearesponsable','$ipusu',NOW())";
        //return ejecutarConsulta($sql);
        $idrolnew=ejecutarConsulta_retornaID($sql);
       
        $num_elementos=0;
        $num_elementos_acc=0;
        $sw=TRUE;
        if(isset($idrolnew)){
            //Verificar permisos de Acceso de Módulos y Submódulos
            while ($num_elementos< count($acceso)){
                $sql_detalle="INSERT INTO acceso(IDROL,IDMODULO,USUREGACC,FECHAREGACC,IPREGACC)VALUES('$idrolnew','$acceso[$num_elementos]','$regusu',NOW(),'$ipusu')";
                
                ejecutarConsulta($sql_detalle) or $sw=FALSE;
                $num_elementos=$num_elementos+1;
            }

            //Verificar permisos de acceso para Acciones
            while ($num_elementos_acc< count($accesoacciones)){
                $sql_detalle_acc="INSERT INTO accesoacciones(IDROL,IDACCION,USUREGACCION,FECHAREGACCION,IPREGACCION)VALUES('$idrolnew','$accesoacciones[$num_elementos_acc]','$regusu',NOW(),'$ipusu')";
                
                ejecutarConsulta($sql_detalle_acc) or $sw=FALSE;
                $num_elementos_acc=$num_elementos_acc+1;
            }
        }
        return $sw;
    }

    //Implementamos un método para editar registros
    public function editar($idrol,$nombrerol,$regusu,$permiso,$ipusu,$acceso,$accesoacciones,$arearesponsable){
        $sql="UPDATE rol SET NOMBREROL = '$nombrerol',PERMISOROL='$permiso',PERMISOAREARES='$arearesponsable' WHERE IDROL='$idrol'";
        ejecutarConsulta($sql);
        
        //Eliminamos todos los permisos asignados para volverlos a registrar
        $sqldel="DELETE FROM acceso WHERE IDROL='$idrol'";
        ejecutarConsulta($sqldel);

        $sqldel_acc="DELETE FROM accesoacciones WHERE IDROL='$idrol'";
        ejecutarConsulta($sqldel_acc);
        
        $num_elementos=0;
        $num_elementos_acc=0;
        $sw=TRUE;
        //Verificar permisos de Acceso de Módulos y Submódulos
        while ($num_elementos< count($acceso)){
            $sql_detalle="INSERT INTO acceso(IDROL,IDMODULO,USUREGACC,FECHAREGACC,IPREGACC)VALUES('$idrol','$acceso[$num_elementos]','$regusu',NOW(),'$ipusu')";
           
            ejecutarConsulta($sql_detalle) or $sw=FALSE;
            
            $num_elementos=$num_elementos+1;
        }

        //Verificar permisos de acceso para Acciones
        while ($num_elementos_acc< count($accesoacciones)){
            $sql_detalle_acc="INSERT INTO accesoacciones(IDROL,IDACCION,USUREGACCION,FECHAREGACCION,IPREGACCION)VALUES('$idrol','$accesoacciones[$num_elementos_acc]','$regusu',NOW(),'$ipusu')";
            
            ejecutarConsulta($sql_detalle_acc) or $sw=FALSE;
            $num_elementos_acc=$num_elementos_acc+1;
        }
        
        return $sw;
    }

    //Implementamos un método para desactivar usuarios
    public function desactivar($idrol){
        $sql="UPDATE rol SET ESTADOROL='0' WHERE IDROL='$idrol'";
        return ejecutarConsulta($sql);
    }
    //Implementamos un método para activar usuarios
    public function activar($idrol){
        $sql="UPDATE rol SET ESTADOROL='1' WHERE IDROL='$idrol'";
        return ejecutarConsulta($sql);
    }
    
    
    
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idrol){
        $sql="SELECT * FROM rol WHERE IDROL='$idrol'";
        #echo $sql;
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function listar(){
        $sql="SELECT r.`IDROL`,r.`NOMBREROL`,GROUP_CONCAT(m.`NOMBREMOD`)MODULOS,r.`ESTADOROL` 
        FROM rol r LEFT OUTER JOIN acceso a ON r.`IDROL`=a.`IDROL`
        LEFT OUTER JOIN modulos m ON a.`IDMODULO`=m.`IDMODULO` GROUP BY 1";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar todos los registros
    public function roles(){
        $sql="SELECT IDROL,NOMBREROL FROM rol";
        return ejecutarConsulta($sql);
    }

    // ------------ ACCESO ---------------------------------------------------
    //Implementar un método para mostrar todos los registros
    public function modulosLista(){
        $sql="SELECT * FROM modulos ORDER BY MODPADRE";
        return ejecutarConsulta($sql);
    }
    
    //Función para listar permisos marcados
    public function listarmarcados($idrol) {
        $sql="SELECT * FROM acceso WHERE IDROL='$idrol'";
        return ejecutarConsulta($sql);
    }

    // ------------ ACCESO ACCIONES ---------------------------------------------------
    //Implementar un método para mostrar todos los registros
    public function accionesLista(){
        $sql="SELECT a.IDACCION, a.ACCIONCODE, a.NOMBREACC, a.DESCRIPCIONACC, a.IDMODULO, m.NOMBREMOD FROM acciones a LEFT OUTER JOIN modulos m ON m.IDMODULO = a.IDMODULO ORDER BY a.IDMODULO";
        return ejecutarConsulta($sql);
    }
    
    //Función para listar permisos marcados
    public function listaraccionesmarcados($idrol) {
        $sql="SELECT * FROM accesoacciones WHERE IDROL='$idrol'";
        return ejecutarConsulta($sql);
    }
    
    //Se agregó query para area responsable
    public function selectAreaResponsable(){ 
        //Validando que muestre solo los activos
        $sql="SELECT * FROM arearesponsable WHERE arearesponsable.ESTADOAREARES = 1";
        return ejecutarConsulta($sql);
    }	
}
?>