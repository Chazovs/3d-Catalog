### Проверка работоспособности

Проверить правильность получаемых данных каталога можно вызвав следующий код
`BX.ajax.runAction('chazov:unimarket.api.catalogcontroller.getcatalog',
{
method: 'POST',
data:   {
sessid: BX.bitrix_sessid(),
}
}
).then((response) => {
console.log(response.data)
});`