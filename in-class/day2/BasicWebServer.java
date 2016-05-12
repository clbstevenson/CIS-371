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
    
    public static void handleConnection(Socket socket, int clientNum) throws IOException {
    
        System.out.printf("Client %d connected.\n", clientNum);

        // Create a BufferedReader to read in from the socket
        BufferedReader in = new BufferedReader(
            new InputStreamReader(socket.getInputStream()));
        // Create a PrintWriter to write output to the socket
        // note: may need to change to PrintStream instead of PrintWriter
        PrintWriter out = new PrintWriter(
            socket.getOutputStream(), true);

        // while there is input, read until a blank line
        String clientRequest; // Continue to read data from the client until
        // there is an empty line
        while (!((clientRequest = in.readLine()).isEmpty())) {
            System.out.println("C:  " + clientRequest);
        }
        System.out.println("Completed reading header.");
        
        //TODO: send response back to client with a blank line at the end
        String serverResponse = "HTTP/1.1 200 OK\n" +
            "Content-Type: text/plain\n" + "Content-Length: 70\n" + 
            "Connection: close\n\n" +  // include a blank line
            "WIP: This is not the real content. Work in Progress";
        out.println(serverResponse);
        out.flush();

        System.out.printf("Closing connection: client %d\n", clientNum);
        socket.close();


        //TODO: read the rest of the data

    }

    public static void main(String[] args) throws IOException {
        final int DEFAULT_PORT = 8080;
        // if specified, use cmd args as port number; otherwise port 8080
        int portNumber = args.length > 1 ? Integer.parseInt(args[0]) : DEFAULT_PORT;
        
        ServerSocket serverSocket = new ServerSocket(portNumber);
        for(int clientNum = 0; true; clientNum++) {
            final Socket socket = serverSocket.accept();
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
