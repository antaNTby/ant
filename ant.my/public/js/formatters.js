export function formatUsd(
    price = 1.0,
    rval = 4,
    decimalSeparator = '.',
    thousandsSeparator = '',
    unitSymbol = '',
) {
    return (
        unitSymbol +
        formatPrice(price, rval, decimalSeparator, thousandsSeparator)
    );
}

export function formatPrice(
    price,
    rval = 2,
    decimalSeparator = '.',
    thousandsSeparator = '',
) {
    // Проверка входных данных
    if (isNaN(price)) {
        return '1.0000'; // Возвращает строку по аналогии с PHP
    }

    return Number(price).toFixed(rval).replace('.', decimalSeparator);
}

export function formatUnp(str) {
    // Удаляем всё, кроме цифр
    const digits = str.replace(/\D/g, '');
    // Преобразуем в формат 123 456 789
    // console.log(formatUnp("12-34.56/789")); // Выведет: 123 456 789
    return digits.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
}
