export const config = {
    /** Частота обновления переменных в текстовом виджете */
    updateVariablesSequence: 10000, // ms

    /** Время, на которое кешируются различные данные о переменных и проектах, что бы не запрашивать данные слишком часто */
    dataCachedTime: 1000 * 10, // ms  (1000 * 10 = 10 секунд)

    archiveBrowserCacheEnabled: true,
};
