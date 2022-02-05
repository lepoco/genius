using System.Collections.Generic;

namespace Genius.Expert.Interfaces
{
    public interface ISolverQuestion
    {
        public int SystemId { get; set; }

        public bool IsMultiple { get; set; }

        public IEnumerable<int> Confirming { get; set; }

        public IEnumerable<int> Negating { get; set; }

        public IEnumerable<int> Indifferent { get; set; }
    }
}