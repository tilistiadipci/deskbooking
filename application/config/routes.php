<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'authentication';
// $route['404_override'] = 'errorpage/index';
$route['translate_uri_dashes'] = FALSE;



$route['media/(:any)'] = 'redirect/media';
$route['file/room/(:any)'] = 'redirect/multimedia';
$route['download/media-logo'] = 'redirect/mediaLogo';
$route['download/media-menubar'] = 'redirect/mediaMenuBar';
$route['download/media-icon'] = 'redirect/mediaIcon';
$route['download/media-bg'] = 'redirect/mediaBG';
$route['konva'] = 'redirect/konva';
$route['konva/floorlist'] = 'redirect/konvaFloorList';
$route['konva/floor-data'] = 'redirect/konvaFloorGetDataId';
$route['konva/floor-area'] = 'redirect/konvaGetFloorRoomArea';
$route['konva/floor-area/save']['POST'] = 'redirect/konvaSaveFloorRoomArea';

$route['download/media-pantry/(:any)'] = 'redirect/mediaPantry';
$route['download/media-pantry-menu/(:any)'] = 'redirect/mediaPantryMenu';
$route['download/signage/(:any)'] = 'redirect/getDownloadSignage';
$route['download/background/(:any)'] = 'redirect/getDownloadBackground';
$route['test/binary'] = 'redirect/testBinary';
$route['qr/(:any)'] = 'redirect/qrMedia';
$route['testmail'] = 'redirect/testMail';

$route['menu'] = 'menu/index';
$route['menu/(:any)'] = 'menu/getMenu';

$route['authentication'] = 'authentication';
$route['authentication/logout'] = 'authentication/logout';
$route['authentication/login']['POST'] = 'authentication/checklogin';

$route['redirect'] = 'redirect/index';
$route['test-door/(:any)/(:any)'] = 'api/access/testdoor';
$route['testall2'] = 'api/access/testall2';
$route['testscript'] = 'menu/test';
$route['testoutlook'] = 'testoutlook';


$route['notification/get']['POST'] = 'admin/notification/get_notify';
// 
$route['dashboard'] = 'admin/dashboard/index';
$route['dashboard/get/chart/booking/(:any)'] = 'admin/dashboard/getChartBooking';
$route['dashboard/get/chart/top-room/(:any)'] = 'admin/dashboard/getChartTopRoom';
$route['dashboard/get/table/ongoing/(:any)/(:any)'] = 'admin/dashboard/getOngoing';

$route['company'] = 'admin/company/index';
$route['company/post/update']['POST'] = 'admin/company/postUpdate';
$route['company/post/media']['POST'] = 'admin/company/postMedia';


$route['automation'] = 'admin/automation/index';
$route['automation/get/data'] = 'admin/automation/getData';
$route['automation/get/edit/(:any)'] = 'admin/automation/getEdit';
$route['automation/get/gendata/(:any)'] = 'admin/automation/genData';
$route['automation/post/create']['POST'] = 'admin/automation/postCreate';
$route['automation/post/delete']['POST'] = 'admin/automation/postDelete';
$route['automation/post/update/(:any)']['POST'] = 'admin/automation/postUpdate';

$route['room'] = 'admin/room/index';
$route['room/get/data'] = 'admin/room/getData';
$route['room/get/merge'] = 'admin/room/getDataMerge';
$route['room/get/single'] = 'admin/room/getDataSingleRoom';
$route['room/get/edit/(:any)'] = 'admin/room/getEdit';
$route['room/post/create']['POST'] = 'admin/room/postCreate';
$route['room/post/delete']['POST'] = 'admin/room/postDelete';
$route['room/post/update/(:any)']['POST'] = 'admin/room/postUpdate';
$route['room/post/update-adv/(:any)']['POST'] = 'admin/room/postUpdateAdv';
$route['room/post/update-adv-checkin/(:any)']['POST'] = 'admin/room/postUpdateAdvCheckin';
$route['room/get/integration']['POST'] = 'admin/room/getDataRoomIntegration';
$route['room/post/integration']['POST'] = 'admin/room/postRoomIntegration';
$route['room/remove/integration']['POST'] = 'admin/room/postRemoveRoomIntegration';
$route['room/remove/all']['POST'] = 'admin/room/removeAllRoom';


$route['building'] = 'admin/building/index';
$route['building/get/data'] = 'admin/building/getData';
$route['building/get/edit/(:any)'] = 'admin/building/getData';
$route['building/post/create']['POST'] = 'admin/building/postCreate';
$route['building/post/delete']['POST'] = 'admin/building/postDelete';
$route['building/post/update/(:any)']['POST'] = 'admin/building/postUpdate';
$route['building/floor'] = 'admin/building/floorIndex';
$route['building/floor/get/data'] = 'admin/building/getFloorData';
$route['building/floor/get/data/update']['POST'] = 'admin/building/getFloorData';
$route['building/floor/post/create']['POST'] = 'admin/building/postFloorCreate';
$route['building/floor/post/update']['POST'] = 'admin/building/postFloorUpdate';
$route['building/floor/post/delete']['POST'] = 'admin/building/postFloorDelete';


$route['building/floor/post/upload']['POST'] = 'admin/building/postFloorUploadImage';



$route['access'] = 'admin/access/index';
$route['access/get/data'] = 'admin/access/getData';
$route['access/get/data/channel'] = 'admin/access/getDataChannel';
$route['access/get/data/integrated/(:any)'] = 'admin/access/getAccessIntegrated';
$route['access/get/edit/(:any)'] = 'admin/access/getEdit';
$route['access/post/create']['POST'] = 'admin/access/postCreate';
$route['access/post/delete']['POST'] = 'admin/access/postDelete';
$route['access/post/assign']['POST'] = 'admin/access/postAssign';
$route['access/post/update/(:any)']['POST'] = 'admin/access/postUpdateAccess';

$route['display'] = 'admin/display/index';
$route['display/get/data'] = 'admin/display/getData';
$route['display/get/data-room'] = 'admin/display/getDataRoomDisplay';
$route['display/post/room']['POST'] = 'admin/display/postCreated';
$route['display/post/room/update']['POST'] = 'admin/display/postUpdate';
$route['display/post/signage']['POST'] = 'admin/display/postSignage';
$route['display/post/enable']['POST'] = 'admin/display/postEnabled';
$route['display/post/delete']['POST'] = 'admin/display/postDeleted';

// $route['display-kiosk/post/signage']['POST'] = 'admin/display/postSignage';
// $route['access/get/data/channel'] = 'admin/access/getDataChannel';
// $route['access/get/data/channel'] = 'admin/access/getDataChannel';
// $route['access/get/data/integrated/(:any)'] = 'admin/access/getAccessIntegrated';
// $route['access/get/edit/(:any)'] = 'admin/access/getEdit';
// $route['access/post/create']['POST'] = 'admin/access/postCreate';
// $route['access/post/delete']['POST'] = 'admin/access/postDelete';
// $route['access/post/assign']['POST'] = 'admin/access/postAssign';
// $route['access/post/update/(:any)']['POST'] = 'admin/access/postUpdateAccess';

$route['profile'] = 'admin/profile/index';
$route['profile/post/update']['POST'] = 'admin/profile/postUpdate';
$route['profile/post/password']['POST'] = 'admin/profile/postPassword';
$route['profile/post/username']['POST'] = 'admin/profile/postUsername';
// 
$route['alocation'] = 'admin/alocation/index';
$route['alocation/get/data/assign/(:any)'] = 'admin/alocation/getDataAssignAlocation';
$route['alocation/get/data/alocation'] = 'admin/alocation/getDataAlocation';
$route['alocation/get/data/type'] = 'admin/alocation/getDataType';
$route['alocation/post/assign']['POST'] = 'admin/alocation/postAssign';
$route['alocation/post/create/type']['POST'] = 'admin/alocation/postCreateType';
$route['alocation/post/update/type']['POST'] = 'admin/alocation/postUpdateType';
$route['alocation/post/delete/type']['POST'] = 'admin/alocation/postDeleteType';
$route['alocation/post/create/alocation']['POST'] = 'admin/alocation/postAlocation';
$route['alocation/post/update/alocation']['POST'] = 'admin/alocation/postAlocation';
$route['alocation/post/delete/alocation']['POST'] = 'admin/alocation/postAlocation';

$route['pantry'] = 'admin/pantry/index';
$route['pantry-package'] = 'admin/pantry/pantryPackage';
$route['pantry/get/data'] = 'admin/pantry/getData';
$route['pantry/get/edit/(:any)'] = 'admin/pantry/getEdit';
$route['pantry/get/satuan'] = 'admin/pantry/getSatuan'; // prefix
$route['pantry/get/menu/(:any)'] = 'admin/pantry/getMenu';
$route['pantry/get/menu-update/(:any)'] = 'admin/pantry/getMenuUpdate';
$route['pantry/get/package'] = 'admin/pantry/getPackage';
$route['pantry/get/package-update/(:any)'] = 'admin/pantry/getPackageUpdate';
$route['pantry/get/variant/(:any)'] = 'admin/pantry/getVariant';
$route['pantry/get/variant-update/(:any)'] = 'admin/pantry/getVariantUpdate';

// menu
$route['pantry-menu'] = 'admin/pantry/indexMenu';
$route['pantry-menu/filter']['POST'] = 'admin/pantry/filterMenu';


$route['pantry/post/create-menu']['POST'] = 'admin/pantry/postCreateMenu';
$route['pantry/post/delete-menu']['POST'] = 'admin/pantry/postDeleteMenu';
$route['pantry/post/update-menu/(:any)']['POST'] = 'admin/pantry/postUpdateMenu';
// satuan
$route['pantry/post/create-satuan']['POST'] = 'admin/pantry/postCreateSatuan';
$route['pantry/post/delete-satuan']['POST'] = 'admin/pantry/postDeleteSatuan';
$route['pantry/post/update-satuan/(:any)']['POST'] = 'admin/pantry/postUpdateSatuan';

$route['pantry/post/create-variant/(:any)']['POST'] = 'admin/pantry/postCreateVariant';
$route['pantry/post/delete-variant/(:any)']['POST'] = 'admin/pantry/postDeleteVariant';
$route['pantry/post/update-variant/(:any)']['POST'] = 'admin/pantry/postUpdateVariant';
// primary
$route['pantry/post/create']['POST'] = 'admin/pantry/postCreate';
$route['pantry/post/delete']['POST'] = 'admin/pantry/postDelete';
$route['pantry/post/update/(:any)']['POST'] = 'admin/pantry/postUpdate';
// Package
$route['pantry/post/create-package']['POST'] = 'admin/pantry/postCreatePackage';
$route['pantry/post/delete-package']['POST'] = 'admin/pantry/postDeletePackage';
$route['pantry/post/update-package/(:any)']['POST'] = 'admin/pantry/postUpdatePackage';

$route['facility'] = 'admin/facility/index';
$route['facility/get/data'] = 'admin/facility/getData';
$route['facility/get/data/detail/(:any)'] = 'admin/facility/getDataDetail';
$route['facility/post/create']['POST'] = 'admin/facility/postCreate';
$route['facility/post/delete']['POST'] = 'admin/facility/postDelete';
$route['facility/post/update/(:any)']['POST'] = 'admin/facility/postUpdate';

$route['employee'] = 'admin/employee/index';
$route['employee/get/data'] = 'admin/employee/getData';
$route['employee/get/edit/(:any)'] = 'admin/employee/getEdit';
$route['employee/get/detail/(:any)'] = 'admin/employee/getDetailEmployee';
$route['employee/get/departement/(:any)'] = 'admin/employee/getDepartement';

$route['employee/qrcode'] = 'admin/employee/qrcode';


// old without falco
$route['employee/post/create']['POST'] = 'admin/employee/postCreateNew';
$route['employee/post/delete']['POST'] = 'admin/employee/postDeleteNew';
$route['employee/post/update/(:any)']['POST'] = 'admin/employee/postUpdateNew';
$route['employee/post/update-vip/(:any)']['POST'] = 'admin/employee/postUpdateNewVip';
// with gb and falco
// $route['employee/post/create']['POST'] = 'admin/employee/postCreateNew';
// $route['employee/post/delete']['POST'] = 'admin/employee/postDeleteNew';
// $route['employee/post/update/(:any)']['POST'] = 'admin/employee/postUpdateNew';

$route['employee/donwload/template'] = 'admin/employee/donwloadTemplate';
$route['employee/upload/file']['POST']  = 'admin/employee/uploadFile';

$route['employee/post/create/new']['POST'] = 'admin/employee/postCreateNew';


$route['user'] = 'admin/user/index';
$route['user/get/group/detail/(:any)'] = 'admin/user/groupDetail';
$route['user/get/notuser'] = 'admin/user/getDataNotUser';
$route['user/get/group'] = 'admin/user/getDataGroup';
$route['user/get/data'] = 'admin/user/getDataUser';
$route['user/get/user/detail/(:any)'] = 'admin/user/getDataUserDetail';
$route['user/post/update/group']['POST'] = 'admin/user/postUpdateGroup';
$route['user/post/user/create']['POST'] = 'admin/user/postCreateUser';
$route['user/post/user/update']['POST'] = 'admin/user/postUpdateUser';
$route['user/post/user/delete']['POST'] = 'admin/user/postDeleteUser';
$route['user/post/user/disable']['POST'] = 'admin/user/postDisableUser';

$route['booking'] = 'admin/booking/index';
$route['booking/get/partisipant']['POST'] = 'admin/booking/getDataPartisipant';
$route['booking/get/data/start/(:any)/end/(:any)'] = 'admin/booking/getData';
$route['booking/filter/schedule'] = 'admin/booking/getFilterScheduleData';


$route['booking/get/data/other/start/(:any)/end/(:any)'] = 'admin/booking/getDataOther';
$route['booking/check/time'] = 'admin/booking/checkDataTime';
$route['booking/check/today/booking'] = 'admin/booking/checkTodayBooking';
$route['booking/check/pick-date/booking/(:any)'] = 'admin/booking/checkPickerBooking';
$route['booking/check/res-date/booking/(:any)/(:any)/(:any)'] = 'admin/booking/checkPickerBookingWithRoom';
$route['booking/attendance/meeting']['POST'] = 'admin/booking/postAttendanceMeeting';
$route['booking/get/extend-meeting'] = 'admin/booking/getExtendTime';
$route['booking/check/available/room'] = 'admin/booking/checkAvailableMeetingRoom';

$route['booking/get/data/alocation/(:any)'] = 'admin/booking/getAlocation';
$route['booking/test'] = 'admin/booking/checkTodayBooking';
$route['booking/post/book']['POST'] = 'admin/booking/postBook';
$route['booking/post/rebook']['POST'] = 'admin/booking/postReBook'; // reschedule
$route['booking/post/cancelbook']['POST'] = 'admin/booking/postCancelBook'; // cancel
$route['booking/post/approvalbook']['POST'] = 'admin/booking/postCancelBook'; // approval
$route['booking/post/endbook']['POST'] = 'admin/booking/postEndMeeting'; // END
$route['booking/post/extend-book']['POST'] = 'admin/booking/setExtendBooking'; // EXTEND

$route['booking/post/delete']['POST'] = 'admin/booking/postDelete'; // delete
$route['booking/get/data/pic/(:any)'] = 'admin/booking/getPICInformation';
// $route['booking/post/create']['POST'] = 'admin/booking/postCreate';
// $route['booking/post/update/(:any)']['POST'] = 'admin/booking/postUpdate';
$route['booking/get/booking-create/page1'] = 'admin/booking/getTodayBooking';
$route['booking/get/booking-create/page2'] = 'admin/booking/getTodayBookingPage2';
$route['booking/check/user-pic'] = 'admin/booking/checkUserPic';

$route['participant/internal/booking/(:any)/employee/(:any)/attendance/(:any)'] = 'admin/partisipant/internal';
	// participant/eksternal/booking/60289-E-METTING/email/tmperdana157@gmail.com/attendance/1
$route['participant/eksternal/booking/(:any)/email/(:any)/attendance/(:any)'] = 'admin/partisipant/eksternal';
$route['participant/internal/set/reason']['POST'] = 'admin/partisipant/setReasonInternal';
$route['approval/meeting-approve'] = 'admin/partisipant/meetingApprove';

// $route['booking'] = 'admin/booking/index';

$route['setting/general'] = 'admin/setting/settingGeneralIndex';
$route['setting/general/get/panty'] = 'admin/setting/settingGeneralGetPantry';
$route['setting/general/data'] = 'admin/setting/settingGeneralData';
$route['setting/smtp-email'] = 'admin/setting/settingSmtpEmailIndex';

$route['setting/license'] = 'admin/License/index';

$route['setting/post/general/pantry-status']['POST'] = 'admin/setting/settingPantryStatusPost';
$route['setting/post/general/pantry']['POST'] = 'admin/setting/settingPantryPost';
$route['setting/post/invoice-config']['POST'] = 'admin/setting/settingInvoiceConfigPost';
$route['setting/post/general']['POST'] = 'admin/setting/settingGeneralPost';
$route['setting/email-smtp/data'] = 'admin/setting/settingEmailSMTPData';
$route['setting/post/email-smtp']['POST'] = 'admin/setting/settingEmailSMTPPost';
$route['setting/email-template/data'] = 'admin/setting/settingEmailTemplateData';
$route['setting/email-template/preview']['POST'] = 'admin/setting/settingEmailTemplatePreview';
$route['setting/post/email-template/invitation']['POST'] = 'admin/setting/settingEmailTemplateInvPost';
$route['setting/post/email-template/reschedule']['POST'] = 'admin/setting/settingEmailTemplateResPost';
$route['setting/post/email-template/cancel']['POST'] = 'admin/setting/settingEmailTemplateCancelPost';


$route['report'] = 'admin/report/index';
$route['report-usage'] = 'admin/report/index';
$route['report-cancel-order'] = 'admin/report/cancelindex';
$route['report-income'] = 'admin/report/incomeindex';
$route['report-outstanding'] = 'admin/report/outstandingindex';
// ===
$route['report/get/room/(:any)/(:any)'] = 'admin/report/getRoomReport';
$route['report/get/room/(:any)/(:any)/(:any)'] = 'admin/report/getRoomReport2';
$route['report/get/room-usage']['POST'] = 'admin/report/getRoomUsageReport';
$route['report/get/room-organizer']['POST'] = 'admin/report/getOrganizerReport';
$route['report/get/room-attendees']['POST'] = 'admin/report/getAttendeesReport';
$route['report/get/room-preview-organizer'] = 'admin/report/previewRoomReport';
$route['report/get/room-preview-attendees'] = 'admin/report/previewRoomReport';

$route['report/get/status-invoice'] = 'admin/report/getInvoiceStatus';
$route['report/submit'] = 'admin/report/submitreport';
$route['report/room/post/status-invoice']['POST'] = 'admin/report/postRoomStatusInvoice';
// $route['report/makereport'] = 'admin/report/makereport';
// $route['report/export-all/room/excell/(:any)/(:any)'] = 'admin/report/exportRoomReportAll';
// $route['report/export-all/room/excell/(:any)/(:any)/(:any)'] = 'admin/report/exportRoomReportAll2';
$route['report/export-all/room/excell'] = 'admin/report/exportReportExcellAllNew';


$route['report/export/room/excell/(:any)/(:any)/(:any)'] = 'admin/report/exportRoomReportPerMeeting';
// ===
$route['report/get/cancel-order-all/(:any)/(:any)'] = 'admin/report/getCancelReport';
$route['report/get/cancel-order-alocation/(:any)/(:any)/(:any)'] = 'admin/report/getCancelReportAlocation';
$route['report/export-all/cancel-order/excell/(:any)/(:any)'] = 'admin/report/exportCancelReportAll';
$route['report/export/cancel-order/excell/(:any)/(:any)/(:any)'] = 'admin/report/exportCancelReportAlocation';
// ===
$route['report/get/income-year/(:any)'] = 'admin/report/getIncomeReportYear';
$route['report/get/income-month/(:any)/(:any)'] = 'admin/report/getIncomeReportMonth';
$route['report/export-year/income/excell/(:any)'] = 'admin/report/exportIncomeReportYear';
$route['report/export-month/income/excell/(:any)/(:any)'] = 'admin/report/exportIncomeReportMonth';
// $route['report/export/cancel-order/excell/(:any)/(:any)/(:any)'] = 'admin/report/exportReportPerMeeting';
// ===
$route['report/get/outstanding-all/(:any)/(:any)'] = 'admin/report/getOutstandingReport';
$route['report/get/outstanding-alocation/(:any)/(:any)/(:any)'] = 'admin/report/getOutstandingReportAlocation';
$route['report/export-all/outstanding/excell/(:any)/(:any)'] = 'admin/report/exportOutstandingReportAll';
$route['report/export/outstanding/excell/(:any)/(:any)/(:any)'] = 'admin/report/exportOutstandingReportAlocation';


$route['invoice'] = 'admin/invoice/index';
$route['invoice/get/data/filter'] = 'admin/invoice/getDataFilter';
// $route['invoice/post/publish-invoice']['POST'] = 'admin/invoice/publishInvoice';
$route['invoice/post/send-invoice']['POST'] = 'admin/invoice/sendInvoice';
$route['invoice/post/confirm-invoice']['POST'] = 'admin/invoice/confirmInvoice';
$route['invoice/post/generate-invoice']['POST'] = 'admin/invoice/generateInvoice';

$route['invoice/export/excell/before/(:any)'] = 'admin/invoice/exportToInvoiceExcell';
$route['invoice/export/excell/send/(:any)'] = 'admin/invoice/exportToInvoiceExcell';
$route['invoice/export/excell/paid/(:any)'] = 'admin/invoice/exportToInvoiceExcell';

$route['invoice/get/data/from/(:any)/to/(:any)'] = 'admin/invoice/getData';
$route['invoice/get/data/years/(:any)'] = 'admin/invoice/getDataYears';
$route['invoice/get/detail-invoice/(:any)'] = 'admin/invoice/getDetailById';
$route['invoice/generate/invoice/years/(:any)'] = 'admin/invoice/generateInvoicebyYears';
$route['invoice/print-excell/alocation/(:any)/(:any)'] = 'admin/invoice/printToExcell';
// $route['invoice/generate/invoice/years']['POST'] = 'admin/invoice/generateInvoicebyYears';
$route['invoice/get/detail/alocation/(:any)/year/(:any)'] = 'admin/invoice/getDataAlocationDetail';
$route['invoice/get/detail/alocation-excell/(:any)/year/(:any)'] = 'admin/invoice/getDataAlocationDetailExcell';
$route['invoice/submit'] = 'admin/invoice/submitreport';
$route['invoice/makeinvoice'] = 'admin/invoice/makeinvoice';

$route['attendance'] = 'admin/dashboard/index';

$route['variable/setting'] = 'admin/variable/setting';

$route['pantry-transaction'] = 'admin/pantrytransaction/index';
$route['pantry-transaction/get/data'] = 'admin/pantrytransaction/getData';
$route['pantry-transaction/get/data-pantry'] = 'admin/pantrytransaction/getPantryData';

// 
$route['locker-system'] = 'admin/locker/index';
$route['locker-system/get/data'] = 'admin/locker/getData';
$route['locker-system/get/data/detail/(:any)'] = 'admin/locker/getDataDetail';
$route['locker-system/post/create']['POST'] = 'admin/locker/postCreate';
$route['locker-system/post/delete']['POST'] = 'admin/locker/postDelete';
$route['locker-system/post/update/(:any)']['POST'] = 'admin/locker/postUpdate';


$route['integration'] = 'admin/integration/index';
$route['integration/save/alarm-config']['POST'] = 'admin/integration/saveAlarmConfig';
$route['integration/alarm/redirect'] = 'admin/integration/alarmRedirect';

$route['integration/open/connection/m365'] = 'admin/integration/m365OpenConnection';
$route['integration/callback/connection/m365'] = 'admin/integration/m365CallbackConnection';
$route['integration/callback/disconnection/m365'] = 'admin/integration/m365CallbackDisonnection';


// 10 JAN 2023


$route['display-kiosk'] = 'admin/display/kioskindex';
$route['display-kiosk/get/data'] = 'admin/display/getKioskData';
$route['display-kiosk/get/data-room'] = 'admin/display/getDataRoomDisplay';
$route['display-kiosk/post/create']['POST'] = 'admin/display/postKioskCreated';
$route['display-kiosk/post/update']['POST'] = 'admin/display/postKioskUpdated';
$route['display-kiosk/post/logout']['POST'] = 'admin/display/logoutKiosk';

$route['beacon-tag'] = 'admin/beacon/index';
$route['beacon-tag/get/data'] = 'admin/beacon/getData';
$route['beacon-tag/get/employee-no-beacon'] = 'admin/beacon/getEmployeeNoBeacon';
$route['beacon-tag/post/create'] = 'admin/beacon/postCreate';
$route['beacon-tag/post/update'] = 'admin/beacon/postUpdate';
$route['beacon-tag/post/delete'] = 'admin/beacon/postDelete';
$route['beacon-tag/post/upload'] = 'admin/beacon/postUpload';

$route['beacon-gateway'] = 'admin/beacon/beaconGatewayIndex';
$route['beacon-gateway/editor'] = 'admin/beacon/beaconGatewayEditor';
$route['beacon-gateway/get/data'] = 'admin/beacon/getBeaconGatewayData';
$route['beacon-gateway/post/create'] = 'admin/beacon/postBeaconGatewayCreate';
$route['beacon-gateway/post/update'] = 'admin/beacon/postBeaconGatewayUpdate';
$route['beacon-gateway/post/delete'] = 'admin/beacon/postBeaconGatewayDelete';

$route['beacon-floor'] = 'admin/beacon/floorIndex';
$route['beacon-floor/get/data'] = 'admin/beacon/getFloorData';
$route['beacon-floor/post/create']['POST'] = 'admin/beacon/postFloorCreate';
$route['beacon-floor/post/update']['POST'] = 'admin/beacon/postFloorUpdate';
$route['beacon-floor/post/delete']['POST'] = 'admin/beacon/postFloorDelete';

$route['beacon-floor-room/create'] = 'admin/beacon/createFloorRoom';
$route['beacon-floor-room/get/data'] = 'admin/beacon/getFloorRoomData';
$route['beacon-floor-room/post/create']['POST'] = 'admin/beacon/postFloorRoomCreate';
// $route['beacon-floor-room/post/update'] = 'admin/beacon/postFloorRoomUpdate';
$route['beacon-floor-room/post/delete'] = 'admin/beacon/postFloorRoomDelete';

$route['beacon-live-monitor'] = 'admin/beacon/indexLv';
$route['beacon-live-monitor/get/data'] = 'admin/beacon/getLvTrs';

$route['beacon-floor-area-room-editor'] = 'admin/beacon/beaconFloorAreaRoomEditor';
$route['beacon-floor-area-room-editor/floorlist'] = 'admin/beacon/beaconFloorAreaRoomEditorFloorList';
$route['beacon-floor-area-room-editor/floor-data'] = 'admin/beacon/beaconFloorAreaRoomEditorFloorGetDataId';
$route['beacon-floor-area-room-editor/floor-area'] = 'admin/beacon/beaconFloorAreaRoomEditorGetFloorRoomArea';
$route['beacon-floor-area-room-editor/floor-area/save']['POST'] = 'admin/beacon/beaconFloorAreaRoomEditorSaveFloorRoomArea';

$route['beacon-monitor-room'] = 'admin/beacon/monitor';
$route['beacon-monitor-room/gateway'] = 'admin/beacon/monitorGetGateway';
$route['beacon-monitor-room/beacon'] = 'admin/beacon/monitorGetBeacon';



// deskbooking

$route['deskmonitor'] = 'admin/deskRoomMonitor/index';


$route['report-desk-usage'] = 'admin/deskReport/index';


$route['deskcontroller'] = 'admin/deskController/index';
$route['deskcontroller/get/data'] = 'admin/deskController/getData';
$route['deskcontroller/get/data/detail/(:any)'] = 'admin/deskController/getDataDetail';
$route['deskcontroller/get/data/controller-initial/(:any)'] = 'admin/deskController/getDataControllerInitial';
$route['deskcontroller/post/reset-controller']['POST'] = 'admin/deskController/resetController';
$route['deskcontroller/post/create']['POST'] = 'admin/deskController/postCreate';
$route['deskcontroller/post/delete']['POST'] = 'admin/deskController/postDelete';
$route['deskcontroller/post/update/(:any)']['POST'] = 'admin/deskController/postUpdate';


$route['deskroom'] = 'admin/deskRoom/index';
$route['deskroom/get/data'] = 'admin/deskRoom/getData';
$route['deskroom/get/edit/(:any)'] = 'admin/deskRoom/getEdit';
$route['deskroom/get/editor/(:any)'] = 'admin/deskRoom/getEditor';
$route['deskroom/get/editor-controller-socket/(:any)'] = 'admin/deskRoom/getSocketZoneController';
$route['deskroom/get/editor-data']['POST'] = 'admin/deskRoom/getEditorData';
// $route['deskroom/get/editor-zone/(:any)'] = 'admin/deskRoom/getEditorZone';
$route['deskroom/editor'] = 'admin/deskRoom/editorIndex';
// $route['deskroom/editor-zone'] = 'admin/deskRoom/editorZoneIndex';
$route['deskroom/editor-zone'] = 'admin/deskRoom/editorZoneIndex2';
$route['deskroom/post/create']['POST'] = 'admin/deskRoom/postCreate';
$route['deskroom/post/delete']['POST'] = 'admin/deskRoom/postDelete';
$route['deskroom/post/update/(:any)']['POST'] = 'admin/deskRoom/postUpdate';
$route['deskroom/post/create-editor-zone']['POST'] = 'admin/deskRoom/postActionEditor';
$route['deskroom/post/delete-editor-zone']['POST'] = 'admin/deskRoom/postDeleteEditor';

$route['deskroom/save/editor-data-position']['POST'] = 'admin/deskRoom/postSavePosition';


$route['desktrs'] = 'admin/deskBooking/index';
$route['desktrs/employee/data'] = 'admin/employee/getData';

$route['desktrs/get/partisipant']['POST'] = 'admin/deskBooking/getDataPartisipant';
// $route['desktrs/get/data/start/(:any)/end/(:any)'] = 'admin/deskBooking/getData';
$route['desktrs/get/filter'] = 'admin/deskBooking/getData';
$route['desktrs/get/data/other/start/(:any)/end/(:any)'] = 'admin/deskBooking/getDataOther';
$route['desktrs/check/time'] = 'admin/deskBooking/checkDataTime';
$route['desktrs/get/book/time'] = 'admin/deskBooking/getDeskBookTime';
$route['desktrs/reschedule/book/time'] = 'admin/deskBooking/getRescheduleDeskBookTime';

$route['desktrs/check/today/booking'] = 'admin/deskBooking/checkTodayBooking';
$route['desktrs/check/pick-date/booking/(:any)'] = 'admin/deskBooking/checkPickerBooking';
$route['desktrs/check/res-date/booking/(:any)/(:any)/(:any)'] = 'admin/deskBooking/checkPickerBookingWithRoom';
$route['desktrs/attendance/meeting']['POST'] = 'admin/deskBooking/postAttendanceMeeting';
$route['desktrs/get/extend-meeting'] = 'admin/deskBooking/getExtendTime';

$route['desktrs/get/data/alocation/(:any)'] = 'admin/deskBooking/getAlocation';
$route['desktrs/test'] = 'admin/deskBooking/checkTodayBooking';
$route['desktrs/post/book']['POST'] = 'admin/deskBooking/postBook';
$route['desktrs/post/rebook']['POST'] = 'admin/deskBooking/postReBook'; // reschedule
$route['desktrs/post/cancelbook']['POST'] = 'admin/deskBooking/postCancelBook'; // cancel
$route['desktrs/post/endbook']['POST'] = 'admin/deskBooking/postEndMeeting'; // END
$route['desktrs/post/extend-book']['POST'] = 'admin/deskBooking/setExtendBooking'; // EXTEND

$route['desktrs/post/delete']['POST'] = 'admin/deskBooking/postDelete'; // delete
$route['desktrs/get/data/pic/(:any)'] = 'admin/deskBooking/getPICInformation';
$route['desktrs/get/booking-create/page1'] = 'admin/deskBooking/getTodayBooking';
$route['desktrs/get/booking-create/page2'] = 'admin/deskBooking/getTodayBookingPage2';
$route['desktrs/get/booking-create/page2/data']['POST'] = 'admin/deskBooking/getTodayBookingPage2Data';
$route['desktrs/export/excell'] = 'admin/deskBooking/exportExcell';
$route['desktrs/export/pdf']= 'admin/deskBooking/exportPdf';



$route['desktrs/check/user-pic'] = 'admin/deskBooking/checkUserPic';


$route['room-place'] = 'place/placeRoom/index';
$route['room-place/get/data-floor-filter'] = 'place/placeRoom/getDataFloorRoom';
$route['room-place/get/data-facility-filter'] = 'place/placeRoom/getDataFacilityRoom';
$route['room-place/get/data-calendar-filter'] = 'place/placeRoom/getFilterCalendarRoom';
$route['room-place/get/data-time-room'] = 'place/placeRoom/getTimeBookByRoom';
// $route['room-place/get/data-room'] = 'place/placeRoom/index';




// =========================================================
// API
// =========================================================


$route['api'] = 'api/apiindex/index';

$route['api/generate-display-meeting-android']['POST'] = 'api/serialNumber/generateDeviceSerialDisplayMeetingAndroid';
$route['api/generate-display-meeting-windows']['POST'] = 'api/serialNumber/generateDeviceSerialDisplayMeetingWindows';
$route['api/generate-display-meeting-mac']['POST'] = 'api/serialNumber/generateDeviceSerialDisplayMeetingMac';

$route['api/test'] = 'api/apiindex/test';
// login api
$route['api/mobile/login']['POST'] = 'api/loginmobile/loginApps';
$route['api/mobile/refresh']['POST'] = 'api/loginmobile/refreshApps';
$route['api/pantry/login']['POST'] = 'api/loginmobile/loginPantry';
$route['api/display/login']['POST'] = 'api/loginmobile/loginDisplay';

$route['api/mobile/module']['POST'] = 'api/menu/getModule';
$route['api/mobile/menu']['POST'] = 'api/menu/getData';


$route['api/display/check-serial']['POST'] = 'api/display/getDisplayBySerial';
$route['api/display/room/list']['POST'] = 'api/room/getRoomList';
$route['api/display/room/merge/list']['POST'] = 'api/room/getMergeRoomList';
$route['api/display/room/id']['POST'] = 'api/room/getRoomId';
$route['api/display/schedule/get-time-booked']['POST'] = 'api/schedule/getTimeForFastBooked';

$route['api/display/schedule/merge/get-time-booked']['POST'] = 'api/schedule/getTimeMergeForFastBooked';
$route['api/display/schedule/get-soon']['POST'] = 'api/schedule/getMeetingListDisplay';
$route['api/display/schedule/merge/list']['POST'] = 'api/schedule/getMeetingMergeListDisplay';

// $route['api/display/schedule/get-soon/today']['POST'] = 'api/display/getMeetingWithMoreRoomListDisplay';
$route['api/display/schedule/get-occupied']['POST'] = 'api/display/getMeetingOccupiedDisplay';
// new des 2023
$route['api/display/schedule/get-soon/today']['POST'] = 'api/display/getMeetingWithMoreRoomListDisplay';
$route['api/display/schedule/get-occupied/today']['POST'] = 'api/display/getMeetingWithMoreRoomOccupiedDisplay';

$route['api/display/schedule/get-occupied/status'] = 'api/schedule/getMeetingOccupiedDisplayAllStatus';
$route['api/display/schedule/get-extendtime']['POST'] = 'api/schedule/getExtendTimeDisplay';
$route['api/display/schedule/set-extendtime']['POST'] = 'api/schedule/setExtendBookingDisplay'; 
$route['api/display/signage/get-signage']['POST'] = 'api/display/getData';
$route['api/display/signage/post-signage/signage']['POST'] = 'api/display/postDisplaySignage';
$route['api/display/signage/post-signage/background']['POST'] = 'api/display/postDisplaySignage';
$route['api/display/schedule/fastbooked']['POST'] = 'api/display/fastBooked';
// $route['api/display/schedule/fastbooked']['POST'] = 'api/schedule/fastBooked';

// SCHECDULE API
$route['api/schedule/list']['POST'] = 'api/schedule/getallschedule';
$route['api/schedule/list/date']['POST'] = 'api/schedule/getallscheduleDate';
$route['api/schedule/list/calendar']['POST'] = 'api/schedule/getallscheduleCalendar';
$route['api/schedule/list-today']['POST'] = 'api/schedule/getListToday';
$route['api/schedule/list-all-today']['POST'] = 'api/schedule/getListAllToday';
$route['api/schedule/list-all-meeting/user']['POST'] = 'api/schedule/getListAllMeeting';
$route['api/schedule/list-all-meeting/general']['POST'] = 'api/schedule/getListAllMeeting';
$route['api/schedule/delete-list']['POST'] = 'api/schedule/deleteScheduleList';
$route['api/schedule/report-meeting/(:any)'] = 'api/schedule/reportMeeting';
$route['api/schedule/list-moved']['POST'] = 'api/schedule/getListMoved';
$route['api/schedule/search-room']['POST'] = 'api/schedule/getListSearchRoom';
$route['api/schedule/booking']['POST'] = 'api/schedule/getBookingById';


$route['api/schedule/get-extendtime']['POST'] = 'api/schedule/getExtendTime';
$route['api/schedule/set-extendtime']['POST'] = 'api/schedule/setExtendBooking'; 

$route['api/schedule/check-reschedule']['POST'] = 'api/schedule/checkRescheduleBookingWithRoom';

$route['api/schedule/active']['POST'] = 'api/schedule/getactivesSchedule';
$route['api/schedule/soon']['POST'] = 'api/schedule/getsoonschedule';
$route['api/schedule/expired']['POST'] = 'api/schedule/getexpiredschedule';
$route['api/schedule/delete']['POST'] = 'api/schedule/deleteScheduleList';
$route['api/schedule/room']['POST'] = 'api/schedule/getDataRoomMeeting';

$route['api/schedule/get/participant']['POST'] = 'api/schedule/getParticipant';


$route['api/checkhdatetime']['POST'] = 'api/schedule/getCheckDateTime';
$route['api/cancelschedule']['POST'] = 'api/schedule/cancel';

// SCHECDULE API POST
$route['api/schedule/post/end-meeting']['POST'] = 'api/schedule/postEndMeeting'; // DISPLAY END MEETING
$route['api/schedule/post/extend']['POST'] = 'api/schedule/extend';
$route['api/schedule/post/end-meeting-mobile']['POST'] = 'api/schedule/postEndMeetingMobile';
$route['api/schedule/post/attend-mobile']['POST'] = 'api/schedule/postAttendMobile';
$route['api/schedule/post/delete-attend-mobile']['POST'] = 'api/schedule/postDeleteAttendMobile';
$route['api/schedule/post/invite-attend-mobile']['POST'] = 'api/schedule/postInviteAttendMobile';

// BOOKING API 
$route['api/booking/get/data/filter']['POST']  = 'api/booking/getFilter';
$route['api/booking/get/data/alocation']['POST']  = 'api/booking/getAlocation';
$route['api/booking/check/today-meeting']['POST'] = 'api/booking/checkTodayBooking';
$route['api/booking/check/pick-meeting']['POST'] = 'api/booking/checkPickBooking';
// $route['api/schedule/post/end-meeting-mobile']['POST'] = 'api/schedule/postEndMeetingMobile';

// 
// 2020
$route['api/getusers']['POST'] = 'api/users/getusersbooking';
$route['api/booking/create']['POST'] = 'api/booking/postCreateBooking';
$route['api/booking/re-create']['POST'] = 'api/booking/postReCreateBooking';
$route['api/booking/update']['POST'] = 'api/booking/postUpdateBooking';
$route['api/booking/cancel']['POST'] = 'api/booking/postCancelBooking';
$route['api/booking/makehost']['POST'] = 'api/booking/postMakeHostBooking';

// 2023 17 OCT 2023
$route['api/booking/create/365']['POST'] = 'api/bookingServices/postCreateBookingBy365';
$route['api/booking/re-create/365']['POST'] = 'api/bookingServices/postCreateBookingBy365';
$route['api/booking/cancel/365']['POST'] = 'api/bookingServices/postCancelBookingBy365';


$route['api/notification/getAll']['POST'] = 'api/notification/getAllNotification';
$route['api/notification/delete']['POST'] = 'api/notification/deleteNotification';

$route['api/report/attendance']['POST'] = 'api/report/getAttendance';
$route['api/report/invitation']['POST'] = 'api/report/getInvitation';
$route['api/report/meeting']['POST'] = 'api/report/getMeeting';
$route['api/report/meeting-download'] = 'api/report/getMeetingDownload';
$route['api/report/export-meeting-excell'] = 'api/report/exportTableToExcell';
$route['api/report/meetingnew']['POST'] = 'api/report/getMeetingNew';


$route['api/report/invoice']['POST'] = 'api/report/getInvoice';
$route['api/report/detail-invoice']['POST'] = 'api/report/getInvoice';

// 2022 13 Januaro 2022
$route['api/booking/booking-upload-attachment']['POST'] = 'api/booking/postUploadAttachmentBooking';
$route['api/schedule/get-meeting-attendance']['POST'] = 'api/schedule/getMeetingAttendance';
$route['api/schedule/get-friendcalender']['POST'] = 'api/schedule/getFriendCalender'; 
$route['api/schedule/get-userlist']['POST'] = 'api/schedule/getUserList'; 
$route['api/schedule/get-meetinglist-room']['POST'] = 'api/schedule/getMeetingListByRoom';
$route['api/user/upload-profile']['POST'] = 'api/users/uploadPhotoProfile';
$route['api/room/get-facility']['POST'] = 'api/room/getFacilityList';
$route['api/room/get-building']['POST'] = 'api/room/getBuildingList';

// 2023 06 November 2023
$route['api/approval/list-approval']['POST'] = 'api/approval/getApproval'; // DISPLAY END MEETING
$route['api/approval/meeting-approval']['POST'] = 'api/approval/meetingApprove'; // DISPLAY END MEETING


// $route['api/booking']['POST'] = 'api/booking/postbooking';
// $route['api/re-booking']['POST'] = 'api/booking/rebooking';
// $route['api/booking/extend-meeting']['POST'] = 'api/booking/rebooking';

// PANTRY API
$route['api/pantry']['POST'] = 'api/pantry/getPantry';

$route['api/pantry/all']['POST'] = 'api/pantry/getAll';
$route['api/pantry/place']['POST'] = 'api/pantry/getPlace';
$route['api/pantry/menu']['POST'] = 'api/pantry/getMenu';
$route['api/pantry/menu/detail/(:any)']['POST'] = 'api/pantry/getMenuDetail';

// $route['api/pantry/process']['POST'] = 'api/pantry/getprocess';
// $route['api/pantry/complete']['POST'] = 'api/pantry/getcomplete';
// $route['api/pantry/done']['POST'] = 'api/pantry/getdone';
// $route['api/pantry/failed']['POST'] = 'api/pantry/getfailed';


$route['api/pantry/menu']['POST'] = 'api/pantry/getmenu';
$route['api/pantry/submit-order']['POST'] = 'api/pantry/postSubmitOrder';
$route['api/pantry/detail-trs']['POST'] = 'api/pantry/getDetailTrs';
$route['api/pantry/cancel-order']['POST'] = 'api/pantry/postCancelOrder';
// $route['api/pantry/detail']['POST'] = 'api/pantry/getdetail';
// $route['api/pantry/post']['POST'] = 'api/pantry/postSubmit';
$route['api/pantry/cancel']['POST'] = 'api/pantry/postCancel'; // cancel order
$route['api/pantry/delete']['POST'] = 'api/pantry/postDelete'; //delete list


$route['api/monitor/activeschedule']['POST'] = 'api/schedule/getactiveroom';
$route['api/monitor/soonschedule']['POST'] = 'api/schedule/getsoonroom';
$route['api/monitor/getUsers']['POST'] = 'api/schedule/getUsersBooking';
$route['api/monitor/getTime']['POST'] = 'api/schedule/getTimeMonitor';
$route['api/monitor/post']['POST'] = 'api/schedule/postmonitor';

// PANTRY DISPLAY
$route['api/display/pantry/listpantry']['POST'] = 'api/pantrydisplay/getlistpantry';
$route['api/display/pantry/orderentry']['POST'] = 'api/pantrydisplay/getorderentry';
$route['api/display/pantry/orderprocess']['POST'] = 'api/pantrydisplay/getorderprocess';
$route['api/display/pantry/ordercomplete']['POST'] = 'api/pantrydisplay/getordercomplete';

$route['api/display/pantry/push/process']['POST'] = 'api/pantrydisplay/pushprocess';
$route['api/display/pantry/push/complete']['POST'] = 'api/pantrydisplay/pushcomplete';
$route['api/display/pantry/push/rejectorder']['POST'] = 'api/pantrydisplay/pushrejectorder';
$route['api/display/pantry/push/rejectitem']['POST'] = 'api/pantrydisplay/pushrejectitem';
$route['api/display/pantry/push/remove']['POST'] = 'api/pantrydisplay/pushremove';

$route['api/get/setting/general']['POST'] = 'api/setting/getDataGeneral';
$route['api/access/open/room/qrcode']['POST'] = 'api/access/checkDoorOpenMeetingQr';
$route['api/access/open/room/qrcodedisplay']['POST'] = 'api/access/checkDoorOpenMeetingQrDisplay';
$route['api/access/open/room/pin']['POST'] = 'api/access/checkDoorOpenMeetingPin';



$route['api/pantry/all']['POST'] = 'api/pantry/getAll';
// $route['api/pantry/place']['POST'] = 'api/pantry/getPlace';
$route['api/pantry/menu']['POST'] = 'api/pantry/getMenu';
// DISPLAY SIGNAGE DEPAN

$route['api/gb/scan']['POST'] = 'api/guestbook/scan';



// DISPLAY SIGNAGE DEPAN
$route['api/display/signage/get-meeting-all']['POST'] = 'api/display/getSignageMeeting';

// 2022-01-05 
// KIOSK DESKBOOKING
$route['api/display/auth-kiosk/serial']['POST'] = 'api/display/kioskAuth';

$route['api/building/get']['POST'] = 'api/building/getData';
$route['api/building/room/get']['POST'] = 'api/building/getDataBuildingRoom';

$route['api/deskbooking/room/get']['POST'] = 'api/deskbooking/getRoom';
$route['api/deskbooking/building/room/get']['POST'] = 'api/deskbooking/getDeskDataBuildingRoom';
$route['api/deskbooking/pointer-active/room/get']['POST'] = 'api/deskbooking/getPointerActiveByRoomAndDateNow';

$route['api/deskbooking/login/qr']['POST'] = 'api/deskbooking/getLoginQr';
$route['api/deskbooking/kiosk/desk/create-book']['POST'] = 'api/deskbooking/postBookKiosk';
$route['api/deskbooking/get/book/time']['POST'] = 'api/deskBooking/getDeskBookTime';




// DESKBOOKING API
$route['api/deskbooking/list']['POST'] = 'api/deskbooking/getallschedule';
$route['api/deskbooking/list/date']['POST'] = 'api/deskbooking/getallscheduleDate';
// $route['api/deskbooking/list/calendar']['POST'] = 'api/deskbooking/getallscheduleCalendar';
$route['api/deskbooking/list-today']['POST'] = 'api/deskbooking/getListToday';
$route['api/deskbooking/list-all-today']['POST'] = 'api/deskbooking/getListAllToday';
// $route['api/deskbooking/list-all-meeting/user']['POST'] = 'api/deskbooking/getListAllMeeting';
// $route['api/deskbooking/list-all-meeting/general']['POST'] = 'api/deskbooking/getListAllMeeting';
$route['api/deskbooking/check/today-meeting']['POST'] = 'api/deskbooking/checkTodayBooking';
$route['api/deskbooking/check/pick-meeting']['POST'] = 'api/deskbooking/checkPickBooking';
$route['api/deskbooking/get-deskroom']['POST'] = 'api/deskbooking/getDataRoom';
$route['api/deskbooking/get-room']['POST'] = 'api/deskbooking/getDataDeskRoomById';

$route['api/deskbooking/delete-list']['POST'] = 'api/deskbooking/deleteScheduleList';
$route['api/deskbooking/report-meeting/(:any)'] = 'api/deskbooking/reportMeeting';
// $route['api/deskbooking/get-deskroom/page2/data']['POST'] = 'admin/deskBooking/getTodayBookingPage2Data';
$route['api/deskbooking/check/time'] = 'api/deskbooking/checkDataTime';
$route['api/deskbooking/get/book/time'] = 'api/deskbooking/getDeskBookTime';
$route['api/deskbooking/reschedule/book/time'] = 'api/deskbooking/getRescheduleDeskBookTime';

$route['api/deskbooking/get/participant']['POST'] = 'api/deskbooking/getParticipant';
$route['api/deskbooking/get-extendtime']['POST'] = 'api/deskbooking/getExtendTime';
$route['api/deskbooking/set-extendtime']['POST'] = 'api/deskbooking/setExtendBooking';

$route['api/deskbooking/mobile/desk/create-book']['POST'] = 'api/deskbooking/postBook';
$route['api/deskbooking/cancel']['POST'] = 'api/deskbooking/postCancelBooking';
$route['api/deskbooking/end-meeting-mobile']['POST'] = 'api/deskbooking/postEndMeetingMobile';
$route['api/deskbooking/mobile/desk/update-book']['POST'] = 'api/deskbooking/postReBook';




// DISPLAY SIGNAGE DEPAN


// $route['user'] = '';
// $route['user']['POST']= '';
// $route['user']['PUT'] = '';
// $route['user']['DELETE'] = '';

// BLIVE 
$route['blive/check/room'] = 'blive/bliveRoom/checkRoom';
$route['blive/check/pantry']['POST'] = 'blive/blivePantry/checkPantry';
$route['blive/button/helpdesk']['POST'] = 'blive/bliveHelpdesk/buttonHelpdesk';

$route['blive/mobile/get/data/helpdesk']['POST'] = 'blive/bliveHelpdesk/getDataMobileHelpdesk';
$route['blive/action/gate1/(:any)'] = 'blive/bliveHelpdesk/buttonHelpdeskGate';

$route['blive/pantry/menu']['POST'] = 'blive/blivePantry/getMenu';
$route['blive/pantry/all']['POST'] = 'blive/blivePantry/getAll';
$route['blive/pantry/place']['POST'] = 'blive/blivePantry/getPlace';
$route['blive/pantry/menu']['POST'] = 'blive/blivePantry/getMenu';

// PANTRY API
$route['blive/pantry']['POST'] = 'blive/pantry/getPantry';

$route['blive/pantry/menu/detail/(:any)']['POST'] = 'blive/blivePantry/getMenuDetail';

$route['blive/pantry/menu']['POST'] = 'blive/blivePantry/getmenu';
$route['blive/pantry/submit-order']['POST'] = 'blive/blivePantry/postSubmitOrder';
$route['blive/pantry/detail-trs']['POST'] = 'blive/blivePantry/getDetailTrs';
$route['blive/pantry/cancel-order']['POST'] = 'blive/blivePantry/postCancelOrder';
// $route['api/pantry/detail']['POST'] = 'api/pantry/getdetail';
// $route['api/pantry/post']['POST'] = 'api/pantry/postSubmit';
$route['blive/pantry/cancel']['POST'] = 'blive/blivePantry/postCancel'; // cancel order
$route['blive/pantry/delete']['POST'] = 'blive/blivePantry/postDelete'; //delete list

$route['blive/view/helpdesk'] = 'blive/bliveHelpdesk/viewHelpdesk';
$route['blive/data/helpdesk'] = 'blive/bliveHelpdesk/getDataHelpdesk';
$route['blive/data/helpdesk/detail'] = 'blive/bliveHelpdesk/getDataHelpdeskDetail';
$route['blive/data/helpdesk/detail/submit']['POST'] = 'blive/bliveHelpdesk/getDataHelpdeskSubmit';




