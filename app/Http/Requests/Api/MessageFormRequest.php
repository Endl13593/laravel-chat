<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MessageFormRequest
 * @property integer to
 * @property string content
 */

class MessageFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to' => 'required|integer|min:1',
            'content' => 'required|string|min:1'
        ];
    }

    public function messages()
    {
        return [
            'to.required' => '- Selecione alguém pra enviar uma mensagem',
            'to.integer'  => '- Selecione alguém pra enviar uma mensagem',
            'to.min'      => '- Selecione alguém pra enviar uma mensagem',

            'content.required' => '- Informe uma mensagem',
            'content.string'   => '- Mensagem inválida',
            'content.min'      => '- Informe uma mensagem',
        ];
    }
}
