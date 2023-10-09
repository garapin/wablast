<?php

use App\Http\Controllers\Admin\ManageUsersController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AutoreplyController;
use App\Http\Controllers\BlastController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MessagesHistoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RestapiController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShowMessageController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;


require_once 'custom-route.php';

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
  
    \UniSharp\LaravelFilemanager\Lfm::routes();
    
});
Route::get('/', function()
{
    return Redirect::to( '/login');
    // OR: return Redirect::intended('/bands'); // if using authentication
});
Route::middleware('auth')->group(function (){

    Route::get('/home',[HomeController::class,'index'])->name('home');
    Route::get('/file-manager',[FileManagerController::class,'index'])->name('file-manager');
    Route::post('/home/setSessionSelectedDevice',[HomeController::class,'setSelectedDeviceSession'])->name('home.setSessionSelectedDevice');
    Route::post('/home/sethook',[HomeController::class,'setHook'])->name('setHook');
    Route::post('/home',[HomeController::class,'store'])->name('addDevice');
    Route::delete('/home',[HomeController::class,'destroy'])->name('deleteDevice');

    Route::get('/scan/{number:body}', ScanController::class)->name('scan');

    Route::get('/autoreply',[AutoreplyController::class,'index'])->name('autoreply');
    Route::post('/autoreply',[AutoreplyController::class,'store'])->name('autoreply');
    Route::delete('/autoreply',[AutoreplyController::class,'destroy'])->name('autoreply.delete');
    Route::post('auto-reply/update/{autoreply:id}',[AutoreplyController::class,'update'])->name('autoreply.update');

    
    
    Route::get('/phonebook',[TagController::class,'index'])->name('phonebook');
    Route::get('/get-phonebook',[TagController::class,'getPhonebook'])->name('getPhonebook');
    Route::delete('/clear-phonebook',[TagController::class,'clearPhonebook'])->name('clearPhonebook');
    Route::get('get-contact/{id}',[ContactController::class,'getContactByTagId']);
    Route::post('/contact/store',[ContactController::class,'store'])->name('contact.store');
    Route::delete('/contact/delete/{contact:id}',[ContactController::class,'destroy'])->name('contact.delete');
    Route::delete('/contact/delete-all/{id}',[ContactController::class,'DestroyAll'])->name('deleteAll');
    Route::post('/contact/import',[ContactController::class,'import'])->name('import');
    Route::get('/contact/export/{id}',[ContactController::class,'export'])->name('exportContact');

  Route::post('/tags',[TagController::class,'store'])->name('tag.store');
  Route::delete('/tags',[TagController::class,'destroy'])->name('tag.delete');
  Route::post('fetch-groups',[TagController::class ,'fetchGroups'])->name('fetch.groups');

  Route::get('/campaigns',[CampaignController::class,'index'])->name('campaigns');
  Route::get('/campaign/create',[CampaignController::class,'create'])->name('campaign.create');
  Route::post('/campaign/store',[CampaignController::class,'store'])->name('campaign.store');
  Route::get('/get-phonebook-list',[CampaignController::class,'getPhonebookList'])->name('getPhonebookList');
  Route::post('/campaign/pause/{id}',[CampaignController::class,'pause'])->name('campaign.pause');
  Route::post('/campaign/resume/{id}',[CampaignController::class,'resume'])->name('campaign.resume');
  Route::delete('/campaign/delete/{id}',[CampaignController::class,'destroy'])->name('campaign.delete');
  Route::get('/campaign/show/{id}',[CampaignController::class,'show'])->name('campaign.show');
  Route::delete('/campaign/clear',[CampaignController::class,'destroyAll'])->name('campaigns.delete.all');
  Route::get('/campaign/blast/{campaign:id}',[BlastController::class,'index'])->name('campaign.blasts');

  Route::post('/preview-message',[ShowMessageController::class,'index'])->name('previewMessage');
  Route::get('/form-message/{type}',[ShowMessageController::class,'getFormByType'])->name('formMessage');
  


  Route::get('/message/test',[MessagesController::class,'index'])->name('messagetest');
  Route::post('/message/test',[MessagesController::class,'store'])->name('messagetest');

  Route::get('/api-docs',RestapiController::class)->name('rest-api');

  Route::get('/user/settings',[UserController::class,'settings'])->name('user.settings');
  Route::post('/user/change-password',[UserController::class,'changePasswordPost'])->name('changePassword');
  Route::post('/user/setting/apikey',[UserController::class,'generateNewApiKey'])->name('generateNewApiKey');

  
  Route::get('/admin/settings',[SettingController::class,'index'])->name('admin.settings');
  Route::post('/settings/server',[SettingController::class,'setServer'])->name('setServer');


  Route::get('/admin/manage-users',[ManageUsersController::class,'index'])->name('admin.manage-users')->middleware('admin');
  Route::post('/admin/user/store',[ManageUsersController::class,'store'])->name('user.store')->middleware('admin');
  Route::delete('/admin/user/delete/{id}',[ManageUsersController::class,'delete'])->name('user.delete')->middleware('admin');
  Route::get('admin/user/edit',[ManageUsersController::class,'edit'])->name('user.edit')->middleware('admin');
  Route::post('admin/user/update',[ManageUsersController::class,'update'])->name('user.update')->middleware('admin');

  Route::get('/messages-history',[MessagesHistoryController::class,'index'])->name('messages.history');
  Route::post('/resend-message',[MessagesHistoryController::class,'resend'])->name('resend.message');


    Route::post('/logout', LogoutController::class)->name('logout');

  

});

Route::middleware('guest')->group(function(){

  Route::get('/login',[LoginController::class,'index'])->name('login');
    Route::get('/register',[RegisterController::class,'index'])->name('register');
    Route::post('/register',[RegisterController::class,'store'])->name('register');
    Route::post('/login',[LoginController::class,'store'])->name('login')->middleware('throttle:5,1');
});
Route::get('/install', [SettingController::class,'install'])->name('setting.install_app');
Route::post('/install', [SettingController::class,'install'])->name('settings.install_app');

Route::post('/settings/check_database_connection',[SettingController::class,'test_database_connection'])->name('connectDB');
Route::post('/settings/activate_license',[SettingController::class,'activate_license'])->name('activateLicense');


