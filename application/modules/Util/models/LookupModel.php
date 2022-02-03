<?php

use chriskacerguis\RestServer\RestController;

class LookupModel extends MainModel
{
    public function Saves($post)
    {
        return $this->Single_Save("tbl_lookup", $post);
    }


    public function List($limit, $project_type)
    {
        $select = "lok_1.id,lok_1.value,ifnull(lok_2.value,'no parent') parent,cat.text category";

        $result = $this->db
            ->select($select)
            ->from("tbl_lookup lok_1")
            ->join("tbl_lookup lok_2", "lok_1.parent_id = lok_2.id", "left")
            ->join(
                "tbl_lookup_category cat",
                "lok_1.lookup_type = cat.value",
                "left"
            )
            ->where("lok_1.project_type", $project_type)
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

    public function load_category()
    {
        $result = $this->db
            ->select("value,text,parent_type")
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
        if (isset($post['project_type'])) {
            $this->db->where("par.project_type", $post['project_type']);
        }

        $result = $this->db
            ->select("par.id value, par.value text")
            ->join("tbl_lookup_category as cat", "cat.value='$post[lookup_type]' and lookup_type=cat.parent_type")
            ->from("tbl_lookup par")
            ->group_by("par.id ")
            ->get()->result();

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
