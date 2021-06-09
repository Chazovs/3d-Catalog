<?php

namespace Chazov\Unimarket\Controller;

use Bitrix\Main\Engine\Controller;

class Catalog extends Controller
{
    /**
     * @return string
     */
    public function getCatalogAction(): string
    {
        return 'Привет';
    }
}

/*BX.ajax.runAction('chazov:unimarket.api.catalog.getcatalog',
                {
                    method: 'POST',
                    data:   {
    sessid: BX.bitrix_sessid(),
                    }
                }
            ).then((response) => {
    console.log(response)

});*/