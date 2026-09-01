module.exports = {
	apps:[
		{name : "expire",script:"./check_desk_booking_expired.js",watch:true},
		{name : "services_book",script:"./check_desk_services.js",watch:true}
	]
}