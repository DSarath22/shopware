import HttpClient from 'src/service/http-client.service';
import Plugin from 'src/plugin-system/plugin.class';

export default class ExampleAjaxPlugin extends Plugin
{
    init(){
        this._client = new HttpClient();
        this.button = this.el.children['ajax-button'];
        // this.button=document.getElementById('ajax-button');
        this.textdiv = this.el.children['ajax-display'];
        // this.textdiv=document.getElementById('ajax-display');
        console.log(this.button);
        console.log(this.textdiv,'div');
        this._registerEvents();
    }
    _registerEvents(){
        this.button.onclick = this._fetch.bind(this);
    }
    _fetch(){
        this._client.get('/example', this._setContent.bind(this), 'application/json', true)
    }
    _setContent(data){
        this.textdiv.innerHTML = JSON.parse(data).timestamp;
    }
}