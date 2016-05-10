/**
 * Outline of a very basic web transaction
 * Created by kurmasz on 4/27/15, Modified by stevecal on 5/10/16
 */
import java.io.*;
import java.net.*;

public class ClientTransaction {

  public static void main(String[] args) {
    String host = "www.cis.gvsu.edu";
    int port = 80;
    String file = "/~kurmasz/Humor/stupid.html";
    //String file = "/~kurmasz/Distiller/HowTo.txt";
    //String file = "/~kurmasz/NoSuchFile.html";
    //String file = "/~kurmasz/buzz1.jpg";

    
    // (1) Create a socket connecting to the host and port set up above.
    try (
        Socket clientSocket = new Socket(host, port);
    // (2) Get the InputStream from the socket and "wrap it up"
        BufferedReader in = new BufferedReader(
            new InputStreamReader(clientSocket.getInputStream()));

    // (3) Get the OutputStream from the socket and "wrap it up"
        // note: writers are able to read more than just 8bit characters
        PrintWriter out = new PrintWriter(clientSocket.getOutputStream());
        DataOutputStream dataOut = 
            new DataOutputStream(clientSocket.getOutputStream());
    ) {

        
    // This web page demonstrates how to set up the socket.
    // http://docs.oracle.com/javase/tutorial/networking/sockets/readingWriting.html
    // If you don't understand what this code is doing:
    //    (a) STOP
    //    (b) Review the I/O Streams readme.txt and sample code posted on GitHub
    //        https://github.com/kurmasz/CS371_SampleCode/tree/master/IOStreams
    //    (c) Ask questions.



    // (4) Send the GET request and the other request headers

    // Use the example client request on this page as a sample:
    // https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol
    // (Go to the page and look for the section named "Example Session")
    //   (a) Remember that you need both the "GET" request and the "host" request header.
    //       The cis web server won't respond without both.
    //   (b) Don't forget to end with a blank line and flush the output stream.
     
        String getRequest = "GET " + file + " HTTP/1.1\n" + 
                             "Host: " + host + "\n\n";
        out.println(getRequest);
        out.flush();
        //dataOut.writeBytes(getRequest);
        //dataOut.flush();


    // (5) Read data from the socket until you get a blank line.
    //     Write each line you receive to System.out
        String response;
        while(!((response = in.readLine()).isEmpty())) { //.equals("\n"))) {
            System.out.println("R:  " + response);
            //if(response.toCharArray()[0] == '\n')
            //    System.out.println("#other new line found");
            //if(response.equals("\n")) 
            //    System.out.println("#new line found");
        }

    
    // (6) Create a FileOutputStream object.
        FileOutputStream outputFile = new FileOutputStream("response.data");
    
    // (7) Read the rest of the data from the socket and write it to a file using
    //     the FileOutputStream you just created.
    //     (Hint:  readLine() returns null when there is no more data to read.)
        //response = "*reset*";
        //while((response = in.readLine()) != null) {
        int responseInt;
        while((responseInt = in.read()) != -1) {
            outputFile.write(responseInt);
        }

        System.out.println("Communication is complete");
    
    } catch (UnknownHostException e) {
        System.err.println("Can't find host " + host);// end try
        System.exit(1);
    } catch (IOException e) {
        System.err.println("Error in getting IO for connection");
        System.exit(1);
    }

  } // end main
  
} // end class
