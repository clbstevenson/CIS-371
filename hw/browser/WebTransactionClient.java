//package edu.gvsu.cis371;

//import edu.gvsu.cis371.MyURL;

import javax.imageio.ImageIO;
import java.awt.image.BufferedImage;
import java.io.*;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.HashMap;
import java.util.Map;

/**
 * Caleb Stevenson
 */

public class WebTransactionClient {


    private PrintWriter out;
    private DataInputStream in;
    private Socket socket;
    private String response;   // The entire response string (e.g., "HTTP/1.1 200 Ok")
    private HashMap<String, String> headers = new HashMap<String, String>();


    public WebTransactionClient(MyURL url) throws IOException {

        //TODO:  Create the sockets and get the data streams.  Then read and store the response and response headers.
        // The code here will be similar to the code from BasicWebTransaction; however,
        // wrap the socket's InputStream in a DataInputStream.  (The DataInputStream will allow you to
        // read both text data and binary data from the InputStream.  You'll get "deprecated" warnings
        // when using the readLine() method.  Ignore them.)

        // For context:  My solution is about 30 lines of Java code.


        // 1. Find the host and port from the provided MyURL url
        String host = url.domainName();
        int port = url.port();

        // 2. Create a socket connecting to the host and port taken from url.
        try  {
            socket = new Socket(host, port);
            // (a) Get the InputStream from the socket and "wrap it up"
            // Use a DataInputStream instead of a BufferedReader, to allow data from non-text files to be read.
            in = new DataInputStream(socket.getInputStream());

            // (b) Get the OutputStream from the socket and "wrap it up"
            // note: writers are able to read more than just 8bit characters
            out = new PrintWriter(socket.getOutputStream());

            // This function should read the response headers and store them in the headers Map. Stop before
            // reading the content.
            // When storing the headers, convert the key to *lower case*
            // The following String methods may be helpful:  split, trim, and toLowerCase

            // Use the example client request on this page as a sample:
            // https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol
            // (Go to the page and look for the section named "Example Session")
            //   (a) Remember that you need both the "GET" request and the "host" request header.
            //       The cis web server won't respond without both.
            //   (b) Don't forget to end with a blank line and flush the output stream.

            String getRequest = "GET " + url.path() + " HTTP/1.1\n" + "Host: " + host + "\n\n";
            out.println(getRequest);
            out.flush();

            // Continue to read from the DataInputStream until there is a blank line (end of header).
            // Note: Ignore the deprecated warning for in.readLine() for now.
            response = "";
            String responseLine;
            System.out.println("DEBUG WTC: before loop");
            while(!((responseLine = in.readLine()).isEmpty())) { //.equals("\n"))) {
                System.out.println("R:  " + responseLine);  // for debugging - print to stdout the response line.
                String[] splitResponse = responseLine.split(":", 2); // Limit of 2, so [0] is tag, [1] is everything else.
                // Add the key pair: (tag -> tag value) to the Map.
                // If there is not a second value after splitting around ":", then add the entire line as the tag value.
                if(splitResponse.length < 2) {
                    headers.put(splitResponse[0].trim().toLowerCase(), responseLine);
                } else {
                    headers.put(splitResponse[0].trim().toLowerCase(), splitResponse[1].trim());
                }
                response += responseLine;
            }
            System.out.println("DEBUG WTC: after loop");

            // Do not read the rest of the data. This is handled by getText or getImage.


        } catch (UnknownHostException e1) {
            System.err.println("Can't find host " + host);// end try
            throw new IOException ("Sorry, can not find host: " + host);
            //System.exit(1);
        } catch (IOException e2) {
            System.err.println("Error in getting IO for connection");
            throw new IOException("Sorry, there was an error in establishing connection");
            //System.exit(1);
        }

    }

    public String getText() throws IOException {

        StringBuffer result = new StringBuffer();

        // TODO: Read the rest of the data from the InputStream as text and return it as a single string.
        // (In this case, using a StringBuffer is more efficient that concatenating String objects.)
        String responseLine;
        // Continue to read until there is no more text from the InputStream
        // readLine() returns null when there is no more data to read.)
        while((responseLine = in.readLine()) != null ) {
            //System.out.println("DEBUG WTC: gettext: reponse = " + responseLine);
            result.append(responseLine);
            // Include a newline character at the end of each line for text files.
            result.append('\n');
            System.out.println("DEBUG WTC: after append");
        }

        System.out.println("DEBUG WTC: after gettext loop");
        return result.toString();
    } // end getText

    public BufferedImage getImage() throws IOException {

        // This function is complete.  The ImageIO class can build an Image object directly from the InputStream.
        // This is why it was important to use a DataInputStream:  The ImageIO class will read binary data from the stream.
        // Had you used BufferedReader or something similar when reading the headers, then it is possible some of the
        // necessary binary data would have been incorrectly loaded into the buffer.

        return ImageIO.read(in);
    }


    public String response() {
        return response;
    }

    public int responseCode() {

        // TODO: retreive the response code (e.g., 200) from the response string and return it as an integer.
        // Split with a limit of 3 to make sure the code (e.g., 200, 404) are at index [1].
        String[] responseSplit = response.split(" ", 3);
        return (Integer.parseInt(responseSplit[1])); // convert to Integer
    }

    public Map<String, String> responseHeaders() {
        // This method is complete.
        return headers;
    }

    public String getHeader(String key) {
        // This method is complete.
        // I convert the key to lower case to avoid problems caused when different web servers use different capitalization.
        return headers.get(key.toLowerCase());
    }


    @Override
    protected void finalize() throws Throwable {
        // This method is complete.
        super.finalize();
        in.close();
        out.close();
        socket.close();
    }
} // end WebTransactionClient
