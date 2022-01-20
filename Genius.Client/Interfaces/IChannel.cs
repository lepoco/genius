using Grpc.Net.Client;

namespace Genius.Client.Interfaces
{
    public interface IChannel
    {
        public GrpcChannel GetChannel();
    }
}
