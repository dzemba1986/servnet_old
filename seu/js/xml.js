function getXMLHttpRequestObject()
{
	try{
		return new XMLHttpRequest();
	}
	catch(e)
	{	
		try
		{
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(e)
		{
			return false;
		}
	}
}
var XMLHttpRequestObject = getXMLHttpRequestObject();

