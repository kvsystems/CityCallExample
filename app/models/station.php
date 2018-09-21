<?

namespace Evie\App\Models;

use Evie\System\Kernel\Model;

class Station extends Model  {

	const GET_DEFAULT_ID = 1;
    const MODIFY_DEFAULT_ID = 0;
    const LIMIT = 1000;
	const FIELD = 'number_azs';

    public function __construct() {
        parent::__construct();
    }
	
	public function getAll( $limit = self::LIMIT )	{		
		return [
			'numbers' => $this->getField('number_azs', $limit),
			'cities'  => $this->getField('city_azs', $limit),
			'regions' => $this->getField('region_azs', $limit),
			'streets'  => $this->getField('street_azs', $limit)
		];
	}	

	public function getRemain( $params = array(),$limit = self::LIMIT )	{		
		return [
			'numbers' => $this->getField('number_azs', $limit, $params),
			'cities'  => $this->getField('city_azs', $limit, $params),
			'regions' => $this->getField('region_azs', $limit, $params),
			'streets'  => $this->getField('street_azs', $limit, $params)
		];
	}

	public function getField( $field = self::FIELD, $limit = self::LIMIT, $data = false )	{
		$sql = "SELECT DISTINCT ".$field." FROM address WHERE id > 0";		
		
		if( $data )	{			
			foreach( $data as $key => $value )	{
				if( $value )	{
					$sql 	.= " AND ".$key."_azs LIKE '%".$value."%'";	
				}		
			}
		}
	
		$sql .= " GROUP BY ".$field." ORDER BY RAND() LIMIT " . (int) $limit;
		return $this->db['gstation']->SelectTable( $sql, [] );
	}

	public function getStationById( $id = self::GET_DEFAULT_ID )    {
        return $this->db['gstation']->SelectString(
            "SELECT * FROM address WHERE id = {?}",
            [ (int) $id ]
        );
    }

    public function removeStationById( $id = self::MODIFY_DEFAULT_ID ) {
        return $this->db['gstation']->Modify(
            "DELETE FROM address WHERE id = {?}",
            [ (int) $id ]
        );
    }

    public function modifyStationById( $id = self::MODIFY_DEFAULT_ID, $params = [] ) {
        $sql = "UPDATE address SET";

        if( !empty( $params ) ) {
            $i = 0;
            foreach( $params as $key => $value )    {
                $sql .= $i == 0
                    ? " " . $key . " = {?}"
                    : ", " . $key . " = {?}";
                $values[] = $value;
                $i++;
            }
        }

        $sql .= " WHERE id = {?}";
        $values[] = (int) $id;

        return $this->db['gstation']->Modify($sql, $values);
    }

    public function createStation( $params = [] ) {
        return $this->db['gstation']->Modify(
            "INSERT INTO address VALUES(NULL, {?}, {?}, {?}, {?})",
            $params
        );
    }

}