import java.text.*;

public class SQSTimestamp{

	public static DateFormat datetimeFormat = new SimpleDateFormat("[HH:mm:ss dd/MM/yyyy]");
	public static DateFormat timeFormat = new SimpleDateFormat("[HH:mm:ss]");
	public static DateFormat dateFormat = new SimpleDateFormat("[dd/MM/yyyy]");


	public static String getTimestamp(String format){
		if (format.equals("date")) return SQSTimestamp.dateFormat.format(new java.util.Date());
		if (format.equals("time")) return SQSTimestamp.timeFormat.format(new java.util.Date());
		return SQSTimestamp.datetimeFormat.format(new java.util.Date());
	}
}

