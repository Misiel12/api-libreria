<?php
/**
 * Created by PhpStorm.
 * User: USUARIO
 * Date: 05/12/2016
 * Time: 11:41
 */

namespace LIBRERIA\API;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Libros
{
    public function getAll(Request $req, Response $res)
    {
        $queryString = $req->getQueryParams();
        $offset = isset($queryString['offset']) ? $queryString['offset'] : 0;
        $limit = isset($queryString['limit']) ? $queryString['limit'] : 6;
        $autor = isset($queryString['autor']) ? $queryString['autor'] : null;
        $search = isset($queryString['search']) ? $queryString['search'] : null;
        try {
            //query para traer los datos de toda la tabla
            $query = "select * from libros where 1=1 ";
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
            //se obtiene la conexion a traves del metodo getconnection
            $conn = Conecction::getConnection();
            $data = $conn->query($query)->fetchAll();
            $cant = $conn->query($count)->fetch();
        } catch (Exception $e) {
            // si hay algun error nos manda un mensaje y regresa un status 500
            return $res->withJSON(['error' => $e->getMessage()], 500);
        }
        // si sale bien regresa los datos como JSON
        return $res->withJSON(['rows' => $data, 'total' => $cant->count]);
    }

    public function getOne($request, $response)
    {   // se toma el id desde la ruta
        $id = $request->getAttribute('id');
        try {
            $query = "select * from libros where id =" . $id;
            //se obtiene la conexion a traves del metodo getconnection
            $conn = Conecction::getConnection();
            // se toma el dato recuperado
            $data = $conn->query($query)->fetchAll();
            if (!$data)
                throw  new Exception("No se pudo recuperar info");
        } catch (Exception $e) {
            // si hay algun error nos manda un mensaje y regresa un status 500
            return $response->withJSON(['error' => $e->getMessage()], 500);
        }
        // si sale bien regresa los datos como JSON
        return $response->withJSON($data);
    }

    public function create($request, $response, $args)
    {   // se obtiene el body
        $json = $request->getBody();

        //se convierte el body a un objeto
        $body = json_decode($json);

        try {
            $query = "insert into libros (titulo_libro,autor,amazon_url,url_img) VALUES('$body->titulo_libro','$body->autor','$body->amazon_url','$body->url_img')";
            //se obtiene la conexion a traves del metodo getconnection
            $conn = Conecction::getConnection();
            $conn->beginTransaction();
            // se toma el dato recuperado
            $data = $conn->query($query);
            if (!$data)
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
    }

    public function modify($request, $response, $args)
    {
        $id = $args['id'];
        $json = $request->getBody();
        $body = json_decode($json);
        try {
            $query = "update libros SET titulo_libro = '$body->titulo_libro',autor = '$body->autor',amazon_url='$body->amazon_url',url_img ='$body->url_img' WHERE id = '$body->id'";
            //se obtiene la conexion a traves del metodo getconnection
            $conn = Conecction::getConnection();
            $conn->beginTransaction();
            // se toma el dato recuperado
            $data = $conn->query($query);
            if (!$data)
                throw  new Exception("No se pudo recuperar info");
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            return $response->withJSON(['error' => $e->getMessage()], 500);
        }
        return $response->withJSON(['success'], 200);
    }

    public function delete($request, $response, $args)
    {
        $id = $request->getAttribute('id');
        try {
            //se hase la query
            $query = "Delete from libros WHERE id = '$id'";
            //se obtiene la conexion a traves del metodo getconnection
            $conn = Conecction::getConnection();
            $conn->beginTransaction();
            // se toma el dato recuperado
            $data = $conn->query($query);
            if (!$data)
                throw  new Exception("No se pudo recuperar info");
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            return $response->withJSON(['error' => $e->getMessage()], 500);
        }
        return $response->withJSON(['id' => $id], 200);
    }


}
