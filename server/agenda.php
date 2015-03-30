<?php

$app->get("/contatos", function () {

	$sql = "SELECT * FROM agenda";
	$stmt = DB::prepare($sql);
	$stmt->execute();
	formatJson($stmt->fetchAll());

});

$app->get("/contato/:id", function ($id) {

	$sql = "SELECT * FROM agenda WHERE id='$id'";
	$stmt = DB::prepare($sql);
	$stmt->execute();
	formatJson($stmt->fetch());

});

$app->post("/contato/:id", function ($id) {

	$data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

	if ($id > 0)
	{
		$sql = "UPDATE agenda SET nome=?,fone=? WHERE id=?";
		$stmt = DB::prepare($sql);
		$stmt->execute(array(
			$data->nome,
			$data->fone,
			$data->id
			)
		);
	}
	else
	{
		$sql = "INSERT INTO agenda (nome,fone)  VALUES (?,?)";
		$stmt = DB::prepare($sql);
		$stmt->execute(array(
			$data->nome,
			$data->fone
			)
		);
		$data->id = DB::lastInsertId();
	}

	formatJson($data);

});

$app->delete("/contato/:id", function ($id) {

	$sql = "DELETE FROM agenda WHERE id=?";
	$stmt = DB::prepare($sql);
	$stmt->execute(array($id));
	formatJson(true);

});