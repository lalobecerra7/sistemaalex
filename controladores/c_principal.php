<?php  
class principal {

	public function _consultar(){
		$omodelo = new m_modelo();
		extract($_POST);

		$query = "SELECT COUNT(*) AS TotalPedidos FROM pedidos WHERE Estatus = 'Pendiente'";
		$row = $omodelo->_consultar($query);
		$numerofilas = $omodelo->numerofilas;
		$PedidosPendientes = '';
		if ($row == "si") {
			echo "Error: " . mysqli_error($omodelo->link);
		} else {
			if ($numerofilas > 0) {
				if ($row[0]["TotalPedidos"] > 1) {
					$PedidosPendientes = '
						<div style="font-weight: bold; cursor: pointer;" class="IrPedidosPendientes">
			            	Tienes <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">'.$row[0]["TotalPedidos"].'</span> pedidos pendientes
			            </div>
					';
				}else if ($row[0]["TotalPedidos"] ==  1) {
					$PedidosPendientes = '
						<div style="font-weight: bold; cursor: pointer;" class="IrPedidosPendientes">
			            	Tienes <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">'.$row[0]["TotalPedidos"].'</span> pedido pendiente
			            </div>
					';
				}else{
					$PedidosPendientes = '
						<div style="font-weight: bold; cursor: pointer;" class="IrPedidosPendientes">
			            	Tienes <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">0</span> pedidos pendientes
			            </div>
					';
				}
			}
			echo $PedidosPendientes;
		}
	}
}
?>