<?php

namespace App\Traits\Admin;

trait ActionButtonTrait
{
    /**
     * @param $actionModel string 模型
     * @return string
     */
    public function editButton($actionModel,$id = null)
    {
        if (!empty($id)){
            $this->id = $id;
        }

            return "<a href='".url('admin').'/'.$actionModel.'/'.$this->id."/edit'><button type='button' onclick='getPage()' class='btn btn-success btn-xs'><i class='fa fa-pencil'> 编辑</i></button></a> ";

    }

    public function deleteButton($actionModel,$id = null)
    {
        if (!empty($id)){
            $this->id = $id;
        }

            $button = "";
            $button .= "<a href='javascript:;' data-id='".$this->id."' class='btn btn-danger btn-xs destroy'>";
            $button .= "<i class='fa fa-trash'> 删除</i>";
            $button .= "<form action='".url('admin/'.$actionModel.'/'.$this->id)."' method='POST'  name='delete_item_".$this->id."'  style='display:none'>";
            $button .= method_field('DELETE').csrf_field();
            $button .= '</form></a> ';
            return $button;

    }



    public function getActionButtons($actionModel,$id = null)
    {
        return $this->editButton($actionModel,$id).$this->deleteButton($actionModel,$id);
    }
}