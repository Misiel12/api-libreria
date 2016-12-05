<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 29/11/2016
 * Time: 10:52
 */
//recupera todos los libros
$app->get('/api/libros', function ($req, $res) {

    $queryString = $req->getQueryParams();
    $offset = isset($queryString['offset']) ? $queryString['offset'] : 0;
    $limit = isset($queryString['limit']) ? $queryString['limit'] : 2;

    $autor = isset($queryString['autor']) ? $queryString['autor'] : null;
    $search = isset($queryString['search']) ? $queryString['search'] : null;

    try {

        $query = "select * from libros where 1=1 ";//  query para traer los datos de toda la tabla
        $count = "select count(*) as count from libros where 1=1 ";


        if ($search) {
            $query .= " and autor like '%" . $search . "%' or titulo_libro like  '%" . $search . "%'  ";
            $count .= " and autor like '%" . $search . "%' or titulo_libro like  '%" . $search . "%'  ";
        }

        if ($autor) {
            $query .= " and autor =" . $autor . " ";
            $count .= " and autor =" . $autor . " ";
        }


        if ($limit) {
            $query .= " LIMIT  " . $limit . " ";

        }

        if ($offset) {
            $query .= " OFFSET " . $offset . " ";

        }


        $conn = Conecction::getConnection(); //se obtiene la conexion a traves del metodo getconnection

        $data = $conn->query($query)->fetchAll();
        $cant = $conn->query($count)->fetch();


    } catch (Exception $e) {

        return $res->withJSON(['error' => $e->getMessage()], 500); // si hay algun error nos manda un mensaje y regresa un status 500
    }


    return $res->withJSON(['rows' => $data, 'total' => $cant->count]); // si sale bien regresa los datos como JSON
});


//recupera un libro
$app->get('/api/libros/{id}', function ($request, $response) {

    $id = $request->getAttribute('id');// se toma el id desde la ruta

    try {

        $query = "select * from libros where id =" . $id;

        $conn = Conecction::getConnection(); //se obtiene la conexion a traves del metodo getconnection


        //$result = $conn->query($query);
        $data = $conn->query($query)->fetchAll();// se toma el dato recuperado
        // $data = $result->fetch_assoc();
        if (!$data)
            throw  new Exception("No se pudo recuperar info");


    } catch (Exception $e) {

        return $response->withJSON(['error' => $e->getMessage()], 500); // si hay algun error nos manda un mensaje y regresa un status 500
    }
    return $response->withJSON($data); // si sale bien regresa los datos como JSON


});


//agrega un libro
$app->post('/api/libros', function ($request, $response, $args) use ($app) {
    require_once('db_connect.php');


    $json = $request->getBody(); // se obtiene el body


    $body = json_decode($json); //se convierte el body a un objeto
    try {
        $query = "insert into libros (titulo_libro,autor,amazon_url,url_img) VALUES('$body->titulo_libro','$body->autor','$body->amazon_url','$body->url_img')";

        $conn = Conecction::getConnection(); //se obtiene la conexion a traves del metodo getconnection
        $conn->beginTransaction();

        //$result = $conn->query($query);
        $data = $conn->query($query);// se toma el dato recuperado
        // $data = $result->fetch_assoc();
        if ($data === FALSE)
            throw  new Exception("No se pudo recuperar info");
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        return $response->withJSON(['error' => $e->getMessage()], 500);
    }


    $last_id = $conn->lastInsertId();

    if (!empty($last_id))
        echo $last_id;

    return $response->withJSON(['success'], 200);

});


//actualiza un libro
$app->put('/api/libros/{id}', function ($request, $response, $args) {


    $id = $args['id'];
    $json = $request->getBody();

    $body = json_decode($json);


    try {

        $query = "update libros SET titulo_libro = '$body->titulo_libro',autor = '$body->autor',amazon_url='$body->amazon_url',url_img ='$body->url_img' WHERE id = '$body->id'";
        $conn = Conecction::getConnection(); //se obtiene la conexion a traves del metodo getconnection
        $conn->beginTransaction();

        //$result = $conn->query($query);
        $data = $conn->query($query);// se toma el dato recuperado
        // $data = $result->fetch_assoc();
        if ($data === FALSE)
            throw  new Exception("No se pudo recuperar info");
        $conn->commit();
        // echo $conn->rowCount() . " records UPDATED successfully";

    } catch (Exception $e) {
        $conn->rollback();
        return $response->withJSON(['error' => $e->getMessage()], 500);
    }


    return $response->withJSON(['success'], 200);


});


// se borra un libro
$app->delete('/api/libros/{id}', function ($request, $response, $args) {


    $id = $request->getAttribute('id');

    try {
        $query = "Delete from libros WHERE id = '$id'"; //se hase la query
        $conn = Conecction::getConnection(); //se obtiene la conexion a traves del metodo getconnection
        $conn->beginTransaction();

        //$result = $conn->query($query);
        $data = $conn->query($query);// se toma el dato recuperado
        // $data = $result->fetch_assoc();
        if ($data === FALSE)
            throw  new Exception("No se pudo recuperar info");
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        return $response->withJSON(['error' => $e->getMessage()], 500);
    }


    return $response->withJSON(['id' => $id], 200);
});

