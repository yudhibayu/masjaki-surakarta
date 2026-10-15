<?php
namespace app\modules\app_setting\helpers;

class InputHelper
{
    public static function inputConvert($data, $req_val) {
		if ($req_val !== $data['value']) {
			$value = $req_val;
		} else  {
			$value = $data['value'];
		}
    	switch ($data['input_type_id']){
			case 1: // TextInput
				return '<div class="col-md-'.$data['size'].'">
							<input class="form-control" type="text" id="'.$data['key'].'" name="'.$data['key'].'" value="'.$value.'">
						</div>';
				break;
			case 2: // TextArea
				return '<div class="col-md-'.$data['size'].'">
							<textarea class="form-control txtarea" rows="'.$data['row'].'" id="'.$data['key'].'" name="'.$data['key'].'">
								'.$value.'
							</textarea>
						</div>';
				break;
		}
    }
}