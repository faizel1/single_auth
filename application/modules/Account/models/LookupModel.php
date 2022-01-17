<?php

use chriskacerguis\RestServer\RestController;

class LookupModel extends MainModel
{


    public function List($limit, $project_type)
    {
        $select ="lok_1.id,lok_1.value,ifnull(lok_2.value,'no parent') parent,cat.text category";

        $result = $this->db
            ->select($select)
            ->from("tbl_lookup lok_1")
            ->join("tbl_lookup lok_2", "lok_1.parent_id = lok_2.id", "left")
            ->join(
                "tbl_lookup_category cat",
                "lok_1.lookup_type = cat.value",
                "left"
            )
            ->where("lok_1.project_type",$project_type)
            // ->where("lok_2.project_type",$project_type)
            ->limit($limit)
            ->get()
            ->result();

        return [
            "data" => $result,
            "statusCode" => $result
                ? RestController::HTTP_OK
                : RestController::HTTP_NON_AUTHORITATIVE_INFORMATION,
        ];
    }

    public function load_category($project_type)
    {
        $result = $this->db
            ->select("value,text,parent_type")
            ->where("project_type",$project_type)
            ->get("tbl_lookup_category")
            ->result();

        return [
            "data" => $result,
            "statusCode" => $result
                ? RestController::HTTP_OK
                : RestController::HTTP_NON_AUTHORITATIVE_INFORMATION,
        ];
    }

    public function load_parent($post)
    {
        $result = $this->db
            ->select("id value, value text")
            ->from("tbl_lookup par")
            ->join(
                "(	select parent_id 
					from tbl_lookup 
					where lookup_type = '$post[lookup_type]'
					) chi",
                "par.id = chi.parent_id"
            )
            // ->where("par.project_type",$post['project_type'])

            ->group_by("par.id,value")
            ->get()
            ->result();

        return [
            "data" => $result,
            "statusCode" => $result
                ? RestController::HTTP_OK
                : RestController::HTTP_NON_AUTHORITATIVE_INFORMATION,
        ];
    }

    //this method is used to load look up value
    public function filter_lookup($post)
    {
        $result = $this->db
            ->select("value, lookup_type text, id")
            ->get_where("tbl_lookup", $post)
            ->result();

        return [
            "data" => $result,
            "statusCode" => $result ? RestController::HTTP_OK : RestController::HTTP_OK,
        ];
    }
}
