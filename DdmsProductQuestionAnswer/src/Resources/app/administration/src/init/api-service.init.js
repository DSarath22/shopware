const Application = Shopware.Application;   
import QAApiService from '../../src/core/service/api/qa-api.service';

Application.addServiceProvider('QAApiService', (container) => {
    const initContainer = Application.getContainer('init');
    return new QAApiService(initContainer.httpClient, container.loginService);
});
