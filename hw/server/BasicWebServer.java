import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;

/**
 * Created by stevecal on 5/12/16
 * Basic structural code borrowed from kurmasz, 
 *      https://github.com/kurmasz/CS371_SampleCode/blob/master/Server
 * This server handles multiple connections by launching new threads
 * for each connection.
 */
public class BasicWebServer {

    //private static PrintStream out;
    
    public static void handleConnection(Socket socket, int clientNum) throws IOException {
    
        System.out.printf("Client %d connected.\n", clientNum);

        // Create a BufferedReader to read in from the socket
        BufferedReader in = new BufferedReader(
            new InputStreamReader(socket.getInputStream()));
        // Create a PrintWriter to write output to the socket
        // note: may need to change to PrintStream instead of PrintWriter
        PrintStream out = new PrintStream(
            socket.getOutputStream(), true);
        //out = new PrintStream(socket.getOutputStream());
        //PrintWriter out = new PrintWriter(
        //    socket.getOutputStream(), true);

        // while there is input, read until a blank line 
        String clientRequest; 
        // Continue to read data from the client until
        // there is an empty line
        String[] headerLines = new String[256];
        int linenum = 0;
        while (!((clientRequest = in.readLine()).isEmpty())) {
            System.out.println(clientRequest);
            headerLines[linenum] = clientRequest;
            linenum++;
        }
        System.out.println("Completed reading header.");

        //: send response back to client with a blank line at the end
        /* The following String and println was the simple demo
         * for in-class assignment 3. 
         * It has been replaced by parsing the header and 
         * sending other content-type, length, etc below.
        String serverResponse = "HTTP/1.1 200 OK\n" +
            "Content-Type: text/plain\n" + "Content-Length: 70\n" + 
            "Connection: close\n"; // include a blank line
            //"WIP: This is not the real content. Work in Progress";
        out.println(serverResponse);
        */
        /*NOTE: chrome requires the content-length to match the content
          if it doesn't, then it will display an error. 
          Firefox does not seem to care if content-length matches
        */

        // 2: parse the header lines
        System.out.println("Parsing header data...");
        // parse the GET line from the client's header request
        System.out.printf("\t%s\n", headerLines[0]);
        String[] getSplit = headerLines[0].split(" ");
        String filenameWithQuery = getSplit[1].substring(1); // store filename as a String (for ease of use).
        System.out.printf("FILE+QUERY: %s\n",filenameWithQuery);
        //String[] filenameArr = filenameWithQuery.split("?");
        int queryIndex = filenameWithQuery.lastIndexOf("?");
        //String filename = filenameArr[0];
        //String querystr = filenameArr[1];
        String filename, querystr;
        if(queryIndex == -1) {
            filename = filenameWithQuery; 
            querystr = "";
        } else {
            filename = filenameWithQuery.substring(0,queryIndex);
            querystr = filenameWithQuery.substring(queryIndex+1);
        }
        System.out.printf("FILE: %s\tQUERY: %s\n", filename, querystr);
        if(filename.equals("mytimetxt")) {
            //serveRavenText
        } else if(filename.equals("mytime")) {
            handleMyTimeHTML(out);
        } else if(filename.equals("who")) {
            //serveWhoHTML
            serveWhoHTML(out);
        } else if(filename.endsWith(".pl")) {
            // run the perl script and display output
            handlePerlScript(out, filename, querystr);
        } else if(filename.endsWith(".sh")) {
            // run the bash script and display output
            handleBashScript(out, filename, querystr);
        } else {
            // Otherwise, assume it is a filename request.
            handleFileRequest(socket, out, clientNum, filename);
        }


        /*
        out.println("HTTP/1.1 200 OK");
        out.println("Content-Type: text/plain");
        out.println("Content-Length: 9");
        out.println("Connection: close");
        out.println("");
        out.println("123456789");
        */
        out.flush();

        // 
        closeConnection(socket, clientNum);


    }

    // This function is used to open a requested file and read the data.
    // If the file can't be found, a 404 response is sent.
    public static void handleFileRequest(Socket socket, PrintStream out, int clientNum, String filename) throws IOException {
        // 3: open the requested file
        // use a FileInputStream instead of FileReader for instances
        // where the file contains non-character data (ie images)
        File file = new File("./" + filename);
        //File file = new File("test1.html");
        if (!file.exists()) {
            // if the server is contacted, but it cannot find the
            // requested file, then the server returns 404 response
            echoPrint(out, "HTTP/1.1 404 NOT FOUND");
            echoPrint(out, "Content-Type: text/plain");
            echoPrint(out, "Content-Length: 49\n");
            // send a message to the client for the 404 response
            echoPrint(out, "404 NOT FOUND\nSorry, we can't find that for you.");
            out.println("\n");
            closeConnection(socket, clientNum);
            return;
        }

        echoPrint(out, "HTTP/1.1 200 OK");
        //out.println("HTTP/1.1 200 OK");
        //System.out.println("R: HTTP/1.1 200 OK"); 

        //TODO: read the rest of the data
        InputStream fileIn = null;
        try {
            // 4: print the required response headers (content-type & -length)
            //    You may base the content-type on the file extension

            // print the content-length of the requested file
            // add 1 to file length for the newline character at end of file
            echoPrint(out,"Content-Length: " + (file.length())); 
            //out.printf("Content-Length: " + file.length());
            //out.printf("Content-Length: %d\n", file.length());
            //System.out.printf("R: Content-Length: %d\n", file.length());

            // print the content-type of the requested file 
            // > finds the index of '.', and then returns the 
            //   substring of the fileName following the '.'
            //String fileName = getSplit[1];
            System.out.printf("\t%s\n", filename);
            System.out.printf("\t%d\n", filename.indexOf('.'));
            String fileType = filename.substring(filename.indexOf('.') + 1);
            echoPrint(out, "Content-Type: " + fileType);

            echoPrint(out, "Connection: close");

            // include a newline after the header
            echoPrint(out, "");

            // begin reading data from the requested file
            // create a FileInputStream so binary data can be read
            fileIn = new FileInputStream(file);
            // continue reading until there is no more data in the file
            int amount_read;
            do {
                // read up to 1024 bytes of raw data, store into byte[]
                byte[] buffer = new byte[1024];
                amount_read = fileIn.read(buffer);
                System.out.printf(":: amount_read = %d\n", amount_read);
                // write data back out to an OutputStream
                // buffer: writes amount_read bytes from buffer
                //      starting at offest 0
                // if end of the file is reached, stop reading
                if(amount_read == -1)
                    break;
                out.write(buffer, 0, amount_read);

            } while(amount_read != -1); // read until end of file
        } catch(FileNotFoundException fnfe) {
            System.err.println("No file:  " + fnfe);
        }
    }

    // Calls the 'who' command and displays the result wrapped in a html format
    private static void serveWhoHTML(PrintStream out) {
        String toPrint = Who.whoHTML();
        out.println("HTTP/1.1 200 OK");
        out.println("Content-Type: text/html");
        out.println("Content-Lenght: " + toPrint.length());
        out.println("Connection: close");
        out.println("");
        out.print(toPrint);
    }

    // Runs the 'mytime' bash script and displays the time wrapped in an html layout
    private static void handleMyTimeHTML(PrintStream out) {
        String toPrint = MyTime.mytimeHTML();
        out.println("HTTP/1.1 200 OK");
        out.println("Content-Type: text/html");
        out.println("Content-Lenght: " + toPrint.length());
        out.println("Connection: close");
        out.println("");
        out.print(toPrint);
    }

    // Runs the specified perl script 'filename' with parameters determined by 'querystr'
    private static void handlePerlScript(PrintStream out, String filename, String querystr) {
        String toPrint = Perl.runPerl(filename, querystr); 
        out.println("HTTP/1.1 200 OK");
        out.println("Content-Type: text/html");
        out.println("Content-Lenght: " + toPrint.length());
        out.println("Connection: close");
        out.println("");
        out.print(toPrint);
    }

    // Runs the specified bash script 'filename' with parameters determined by 'querystr'
    private static void handleBashScript(PrintStream out, String filename, String querystr) {
        File file = new File(filename);
        if(!file.exists()) {
            echoPrint(out, "HTTP/1.1 404 NOT FOUND");
            echoPrint(out, "Content-Type: text/plain");
            echoPrint(out, "Connection: close");
            echoPrint(out, "Content-Length: 49\n");
            // send a message to the client for the 404 response
            echoPrint(out, "404 NOT FOUND\nSorry, we can't find that for you.");
        } else {

            String dataToPrint = Bash.runBash(filename, querystr); 
            String title = "<html><head><title>Bash Script " + filename + 
                "</title></head>";
            String endHTML = "</html>";
            out.println("HTTP/1.1 200 OK");
            out.println("Content-Type: text/html");
            out.println("Content-Length: " + dataToPrint.length() + 
                title.length() + endHTML.length());
            out.println("Connection: close");
            out.println("");

            out.println(title); 
            out.println(dataToPrint);
            out.println(endHTML);
        }
    }

    public static void closeConnection(Socket socket, int clientNum) throws IOException{
        System.out.printf("Closing connection: client %d\n", clientNum);
        System.out.println();
        socket.close();
    }

    // echos @text to both the OutputStream and stdout
    public static void echoPrint(PrintStream out, String text) {
        out.println(text);
        System.out.printf("R: " + text + "\n");
    }


    public static void main(String[] args) throws IOException {
        final int DEFAULT_PORT = 8080;
        // if specified, use cmd args as port number; otherwise port 8080
        int portNumber = args.length >= 1 ? Integer.parseInt(args[0]) : DEFAULT_PORT;
        
        ServerSocket serverSocket = new ServerSocket(portNumber);
        for(int clientNum = 0; true; clientNum++) {
            final Socket socket = serverSocket.accept();
            // Only accept connections from the connection running the server.
            if (!socket.getInetAddress().isLoopbackAddress()) {
                return;
            }
            final int localClientNum = clientNum;
            // Create a new Thread for each client connection
            // to allow multiple clients to be connected concurrently
            new Thread(new Runnable() {
                public void run() {
                    try {
                        // handle the connection for client number @localClientNum
                        handleConnection(socket, localClientNum);
                    } catch(IOException e) {
                        System.err.println("Oops!  " + e);
                        System.out.println();
                        e.printStackTrace();
                    }
                }
            }).start();
        }

    }
}
