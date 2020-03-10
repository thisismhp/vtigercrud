<?php /** @noinspection PhpUnused */

namespace VTigerCrud;

use Salaros\Vtiger\VTWSCLib\WSException;
use Salaros\Vtiger\VTWSCLib\Entities;
use Salaros\Vtiger\VTWSCLib\WSClient;

class VtigerCrud
{
    const USE_ACCESSKEY = WSClient::USE_ACCESSKEY;

    const USE_PASSWORD = WSClient::USE_PASSWORD;

    /**
     * main entity for crud!
     *
     * @var Entities
     */
    private $entities;

    /**
     * Main constructor.
     *
     * @param string $host
     * @param string|integer $username
     * @param string|integer $secret
     * @param int $loginMode
     * @param string $wsBaseURL
     * @param int $requestTimeout
     * @throws WSException
     */
    public function __construct($host, $username, $secret, $loginMode = self::USE_ACCESSKEY, $wsBaseURL = 'webservice.php', $requestTimeout = 0)
    {
        try {
            $this->entities = new Entities(new WSClient($host, $username, $secret, $loginMode, $wsBaseURL, $requestTimeout));
        } catch (WSException $e) {
            throw $e;
        }
    }

    /**
     * get all records of module
     *
     * @param array $module
     * @param array $select
     * @return array
     * @throws WSException
     */
    public function all(array $module, array $select = []){
        return $this->entities->findMany($module['name'], [], $select);
    }

    /**
     * create a new record of module
     *
     * @param array $module
     * @param array $params
     * @return array
     * @throws WSException
     */
    public function create(array $module, array $params){
        return $this->entities->createOne($module['name'], $params);
    }

    /**
     * get a record from module
     *
     * @param array $module
     * @param mixed $id
     * @param array $select
     * @return array|null
     */
    public function show(array $module, $id, array $select = []){
        return ($eid = $this->getEntityId($module, $id))?$this->entities->findOneByID($module['name'], $eid, $select):null;
    }

    /**
     * update a record of module
     *
     * @param array $module
     * @param mixed $id
     * @param array $params
     * @return array|null
     * @throws WSException
     */
    public function update(array $module, $id, array $params)
    {
        return ($eid = $this->getEntityId($module, $id))?$this->entities->updateOne($module['name'], $eid, $params):null;
    }

    /**
     * delete a record of module
     *
     * @param array $module
     * @param mixed $id
     * @return array|null
     */
    public function delete(array $module, $id)
    {
        return ($eid = $this->getEntityId($module, $id))?$this->entities->deleteOne($module['name'], $eid):null;
    }

    /**
     * get module entity id by your custom key
     *
     * @param array $module
     * @param mixed $id
     * @return string
     */
    public function getEntityId(array $module, $id)
    {
        return $this->entities->getID($module['name'], ["{$module['key']}" => $id]);
    }

    /**
     * get \Salaros\Vtiger\VTWSCLib\Entities class object
     *
     * @return Entities
     */
    public function entities(){
        return $this->entities;
    }

}