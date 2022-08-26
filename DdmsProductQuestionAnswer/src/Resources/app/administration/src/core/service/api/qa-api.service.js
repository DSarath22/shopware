import ApiService from 'src/core/service/api.service';

class QAApiService  extends ApiService 
{
    constructor(httpClient, loginService, apiEndpoint = 'qa') {
        super(httpClient, loginService, apiEndpoint);
    }

    
}

export default QAApiService;
