<?php

class EIndentColumn extends CDataColumn
{
    protected function renderDataCellContent($row,$data)
    {
        if($this->value!==null)
            $value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
        elseif($this->name!==null)
            $value=CHtml::value($data,$this->name);

        if ( $value === null ) {
            $out = $this->grid->nullDisplay;
        } else {
            $out = str_repeat("<span class='indent'></span>", $data->getIndent()) . $this->grid->getFormatter()->format($value,$this->type);
        }
        echo $out;
    }
}